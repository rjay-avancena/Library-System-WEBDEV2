

CREATE DATABASE IF NOT EXISTS LIBRARYSYSTEM;
USE LIBRARYSYSTEM;


DROP TABLE IF EXISTS Payment;
DROP TABLE IF EXISTS Penalty;
DROP TABLE IF EXISTS Reservation;
DROP TABLE IF EXISTS Borrow;
DROP TABLE IF EXISTS Clearance;
DROP TABLE IF EXISTS Book;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Semester;
DROP TABLE IF EXISTS Users;


-- CREATE TABLES


-- 1. CATEGORY TABLE
CREATE TABLE Category (
    Category_ID INT PRIMARY KEY AUTO_INCREMENT,
    Category_Name VARCHAR(100) NOT NULL UNIQUE
);

-- 2. USERS TABLE
CREATE TABLE Users (
    User_ID INT PRIMARY KEY AUTO_INCREMENT,
    First_Name VARCHAR(100) NOT NULL,
    Last_Name VARCHAR(100) NOT NULL,
    Role ENUM('Student', 'Teacher', 'Librarian', 'Staff') NOT NULL,
    Email VARCHAR(150) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Contact_Number VARCHAR(20),
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. SEMESTER TABLE
CREATE TABLE Semester (
    Semester_ID INT PRIMARY KEY AUTO_INCREMENT,
    Semester_Term VARCHAR(50) NOT NULL,
    Start_Date DATE NOT NULL,
    End_Date DATE NOT NULL,
    Academic_Year VARCHAR(20) NOT NULL,
    Student_Borrow_Limit INT DEFAULT 3,
    Status ENUM('Active', 'Completed') DEFAULT 'Active'
);

-- 4. BOOK TABLE
CREATE TABLE Book (
    Book_ID INT PRIMARY KEY AUTO_INCREMENT,
    Title VARCHAR(255) NOT NULL,
    Author VARCHAR(255) NOT NULL,
    ISBN VARCHAR(20) UNIQUE,
    Category_ID INT,
    Publisher VARCHAR(150),
    Book_Price DECIMAL(10,2) NOT NULL,
    Total_Copies INT NOT NULL DEFAULT 1,
    Copies_Available INT NOT NULL DEFAULT 1,
    Status ENUM('Available', 'Archived') DEFAULT 'Available',
    Added_By INT,
    Added_Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Category_ID) REFERENCES Category(Category_ID),
    FOREIGN KEY (Added_By) REFERENCES Users(User_ID)
);

-- 5. BORROW TABLE
CREATE TABLE Borrow (
    Borrow_ID INT PRIMARY KEY AUTO_INCREMENT,
    User_ID INT NOT NULL,
    Book_ID INT NOT NULL,
    Semester_ID INT,
    Borrow_Date DATE NOT NULL,
    Due_Date DATE NOT NULL,
    Return_Date DATE NULL,
    Status ENUM('Borrowed', 'Returned', 'Lost', 'Overdue') DEFAULT 'Borrowed',
    Renewal_Count INT DEFAULT 0,
    Processed_By INT NOT NULL,
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID),
    FOREIGN KEY (Book_ID) REFERENCES Book(Book_ID),
    FOREIGN KEY (Semester_ID) REFERENCES Semester(Semester_ID),
    FOREIGN KEY (Processed_By) REFERENCES Users(User_ID)
);

-- 6. RESERVATION TABLE
CREATE TABLE Reservation (
    Reservation_ID INT PRIMARY KEY AUTO_INCREMENT,
    User_ID INT NOT NULL,
    Book_ID INT NOT NULL,
    Reservation_Date DATETIME DEFAULT CURRENT_TIMESTAMP,
    Expiry_Date DATE NOT NULL,
    Status ENUM('Active', 'Fulfilled', 'Expired', 'Cancelled') DEFAULT 'Active',
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID),
    FOREIGN KEY (Book_ID) REFERENCES Book(Book_ID)
);

-- 7. PENALTY TABLE
CREATE TABLE Penalty (
    Penalty_ID INT PRIMARY KEY AUTO_INCREMENT,
    Borrow_ID INT NOT NULL,
    User_ID INT NOT NULL,
    Amount DECIMAL(10,2) NOT NULL,
    Penalty_Type ENUM('Late', 'Lost', 'Damage') DEFAULT 'Late',
    Description TEXT,
    Issued_Date DATE NOT NULL,
    Status ENUM('Unpaid', 'Paid', 'Waived') DEFAULT 'Unpaid',
    FOREIGN KEY (Borrow_ID) REFERENCES Borrow(Borrow_ID),
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID)
);

-- 8. PAYMENT TABLE
CREATE TABLE Payment (
    Payment_ID INT PRIMARY KEY AUTO_INCREMENT,
    User_ID INT NOT NULL,
    Penalty_ID INT,
    Amount DECIMAL(10,2) NOT NULL,
    Payment_Date DATETIME DEFAULT CURRENT_TIMESTAMP,
    Method ENUM('Cash', 'Card', 'Online') DEFAULT 'Cash',
    Processed_By INT NOT NULL,
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID),
    FOREIGN KEY (Penalty_ID) REFERENCES Penalty(Penalty_ID),
    FOREIGN KEY (Processed_By) REFERENCES Users(User_ID)
);

-- 9. CLEARANCE TABLE
CREATE TABLE Clearance (
    Clearance_ID INT PRIMARY KEY AUTO_INCREMENT,
    User_ID INT NOT NULL,
    Semester_ID INT NOT NULL,
    Academic_Year VARCHAR(20) NOT NULL,
    Library_Clearance ENUM('Cleared', 'Not Cleared', 'Pending') DEFAULT 'Pending',
    Clearance_Date DATE NULL,
    Cleared_By INT,
    Remarks TEXT,
    FOREIGN KEY (User_ID) REFERENCES Users(User_ID),
    FOREIGN KEY (Semester_ID) REFERENCES Semester(Semester_ID),
    FOREIGN KEY (Cleared_By) REFERENCES Users(User_ID)
);


-- TRIGGERS FOR AUTOMATIC UPDATES


-- Trigger: Decrease book availability when borrowed
DELIMITER $$
CREATE TRIGGER decrease_book_availability
AFTER INSERT ON Borrow
FOR EACH ROW
BEGIN
    IF NEW.Status = 'Borrowed' THEN
        UPDATE Book 
        SET Copies_Available = Copies_Available - 1 
        WHERE Book_ID = NEW.Book_ID;
    END IF;
END$$
DELIMITER ;

-- Trigger: Increase book availability when returned
DELIMITER $$
CREATE TRIGGER increase_book_availability
AFTER UPDATE ON Borrow
FOR EACH ROW
BEGIN
    IF OLD.Status = 'Borrowed' AND NEW.Status = 'Returned' THEN
        UPDATE Book 
        SET Copies_Available = Copies_Available + 1 
        WHERE Book_ID = NEW.Book_ID;
    END IF;
END$$
DELIMITER ;

-- Trigger: Auto-mark overdue books
DELIMITER $$
CREATE TRIGGER check_overdue_status
BEFORE UPDATE ON Borrow
FOR EACH ROW
BEGIN
    IF NEW.Status = 'Borrowed' AND NEW.Due_Date < CURDATE() AND NEW.Return_Date IS NULL THEN
        SET NEW.Status = 'Overdue';
    END IF;
END$$
DELIMITER ;

-- USEFUL VIEWS


-- View: Check student borrow count per semester
CREATE VIEW Student_Borrow_Count AS
SELECT 
    u.User_ID,
    CONCAT(u.First_Name, ' ', u.Last_Name) AS Student_Name,
    b.Semester_ID,
    COUNT(b.Borrow_ID) AS Active_Borrows,
    s.Student_Borrow_Limit
FROM Users u
LEFT JOIN Borrow b ON u.User_ID = b.User_ID AND b.Status = 'Borrowed'
LEFT JOIN Semester s ON b.Semester_ID = s.Semester_ID
WHERE u.Role = 'Student'
GROUP BY u.User_ID, b.Semester_ID;

-- View: Available books
CREATE VIEW Available_Books AS
SELECT 
    b.Book_ID,
    b.Title,
    b.Author,
    b.ISBN,
    c.Category_Name,
    b.Publisher,
    b.Copies_Available,
    b.Book_Price
FROM Book b
LEFT JOIN Category c ON b.Category_ID = c.Category_ID
WHERE b.Status = 'Available' AND b.Copies_Available > 0;

-- View: Overdue books
CREATE VIEW Overdue_Books AS
SELECT 
    br.Borrow_ID,
    CONCAT(u.First_Name, ' ', u.Last_Name) AS Borrower_Name,
    u.Role,
    b.Title AS Book_Title,
    br.Borrow_Date,
    br.Due_Date,
    DATEDIFF(CURDATE(), br.Due_Date) AS Days_Overdue
FROM Borrow br
JOIN Users u ON br.User_ID = u.User_ID
JOIN Book b ON br.Book_ID = b.Book_ID
WHERE br.Status IN ('Borrowed', 'Overdue') 
AND br.Due_Date < CURDATE()
AND br.Return_Date IS NULL;


-- SAMPLE DATA


-- Categories
INSERT INTO Category (Category_Name) VALUES
('Sport'),
('Travel'),
('Aviation'),
('Programming'),
('Cooking'),
('Fiction');

-- Users
INSERT INTO Users (First_Name, Last_Name, Role, Email, Password, Contact_Number) VALUES
('John', 'Doe', 'Student', 'john.student@gmail.com', '12345', '09123456789'),
('Maria', 'Santos', 'Student', 'maria.student@gmail.com', '23456', '09187654321'),
('Peter', 'Cruz', 'Student', 'peter.student@gmail.com', '34567', '09176543210'),
('Ana', 'Reyes', 'Teacher', 'ana.teacher@gmail.com', '45678', '09165432109'),
('Mark', 'Garcia', 'Teacher', 'mark.teacher@gmail.com', '56789', '09154321098'),
('Liam', 'Smith', 'Librarian', 'liam.librarian@gmail.com', '67890', '09143210987'),
('Eve', 'Johnson', 'Staff', 'eve.staff@gmail.com', '78901', '09132109876'),
('James', 'Wilson', 'Staff', 'james.staff@gmail.com', '89012', '09121098765');

-- Semesters
INSERT INTO Semester (Semester_Term, Start_Date, End_Date, Academic_Year, Student_Borrow_Limit, Status) VALUES
('First Semester', '2024-08-15', '2024-12-20', '2024-2025', 3, 'Active'),
('Second Semester', '2025-01-10', '2025-05-30', '2024-2025', 3, 'Active');

-- Books
INSERT INTO Book (Title, Author, ISBN, Category_ID, Publisher, Book_Price, Total_Copies, Copies_Available, Added_By) VALUES
('The Complete Guide to Basketball', 'Michael Jordan', '978-1234567890', 1, 'Sports Publishing', 850.00, 5, 5, 6),
('Soccer: The Ultimate Training Manual', 'David Beckham', '978-1234567891', 1, 'Athletic Press', 750.00, 4, 4, 6),
('Lonely Planet Southeast Asia', 'Travel Writers', '978-2234567890', 2, 'Lonely Planet', 1200.00, 6, 6, 6),
('Flight Training Manual', 'Aviation Experts', '978-3234567890', 3, 'Aviation Press', 2500.00, 3, 3, 6),
('Clean Code', 'Robert C. Martin', '978-4234567890', 4, 'Prentice Hall', 1650.00, 8, 7, 6),
('The Joy of Cooking', 'Irma S. Rombauer', '978-5234567890', 5, 'Scribner', 950.00, 4, 4, 6),
('To Kill a Mockingbird', 'Harper Lee', '978-6234567890', 6, 'HarperCollins', 850.00, 5, 4, 6),
('1984', 'George Orwell', '978-6234567891', 6, 'Penguin Books', 750.00, 6, 6, 6);

-- Borrows
INSERT INTO Borrow (User_ID, Book_ID, Semester_ID, Borrow_Date, Due_Date, Status, Processed_By) VALUES
(1, 1, 1, '2024-09-01', '2024-09-15', 'Borrowed', 7),
(1, 2, 1, '2024-09-05', '2024-09-19', 'Borrowed', 7),
(2, 3, 1, '2024-08-20', '2024-09-03', 'Returned', 7),
(4, 5, 1, '2024-08-25', '2024-12-20', 'Borrowed', 7),
(5, 7, 1, '2024-09-10', '2024-12-20', 'Borrowed', 8);

-- Update return date for returned books
UPDATE Borrow SET Return_Date = '2024-09-02' WHERE Status = 'Returned';

-- Reservations
INSERT INTO Reservation (User_ID, Book_ID, Reservation_Date, Expiry_Date, Status) VALUES
(2, 2, '2024-09-15 10:30:00', '2024-09-22', 'Active'),
(3, 5, '2024-09-16 14:15:00', '2024-09-23', 'Active');

-- Clearance
INSERT INTO Clearance (User_ID, Semester_ID, Academic_Year, Library_Clearance, Remarks) VALUES
(1, 1, '2024-2025', 'Not Cleared', 'Has 2 unreturned books'),
(2, 1, '2024-2025', 'Cleared', 'All books returned'),
(4, 1, '2024-2025', 'Pending', 'Must return all books by semester end');
