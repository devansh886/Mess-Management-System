# Fix for missing student options

## Why the screens are empty
The student pages are designed to show their main controls only after the logged-in
student has a row in `MessJoins` for the current month. The screenshots show that
the current-month membership lookup returns no mess (`details.mess` is NULL).
The previous-month dropdown also requires older `MessJoins` rows, and the rating
list requires rows in `Rating`.

## Set up a mess admin (only if you do not already have one)
Open phpMyAdmin -> database `mess_database` -> SQL and run the following example.
Change the sample values before running it. `MessCoordinator` is the admin login
username and `MessPassword` is the admin password.

```sql
INSERT INTO Mess (MessID, MessPassword, MessCoordinator, MessName, PerDayRate)
VALUES (1001, 'change-this-password', 'messadmin', 'A Mess', 100)
ON DUPLICATE KEY UPDATE
  MessPassword = VALUES(MessPassword),
  MessCoordinator = VALUES(MessCoordinator),
  MessName = VALUES(MessName),
  PerDayRate = VALUES(PerDayRate);
```

## Add the student to this month's mess
1. Sign out of the student account.
2. Sign in using the Mess Admin option, username `messadmin`, and the password
   you set above (or your existing admin credentials).
3. Open **Mess Card Entry**.
4. Search for the student's exact roll number and click **Add**.
5. Sign out and sign back in as the student. Reload the page.

The student should then see the forum comment box and rating control. Current Month
will show extras only after extras are recorded. Previous Months will show a month
selector only after older month memberships/bills exist.

## Verify the records
Run these queries in phpMyAdmin, replacing the roll number if needed:

```sql
SELECT * FROM Mess;
SELECT * FROM MessJoins WHERE RollNo = 'YOUR_ROLL_NUMBER'
ORDER BY StartDate DESC;
SELECT * FROM ExtrasTaken WHERE RollNo = 'YOUR_ROLL_NUMBER';
SELECT * FROM Rating WHERE RollNo = 'YOUR_ROLL_NUMBER';
```

Do not insert a fake previous bill or rating just to make the page non-empty. Those
sections are data-driven and should display records only when they actually exist.

## Code compatibility fix included
`api/get.php` used PHP's removed `split()` function while building the previous-month
list. This package replaces those calls with `explode()`, which works on current PHP.
