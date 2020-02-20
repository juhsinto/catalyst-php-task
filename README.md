
# Catalyst Task
Code challenge: PHP script that parses a csv file and inserts data to a PostgreSQL Database table


# Environment
The script would ideally be run with the following execution environment:
1. Ubuntu 18.04
2. PHP Version - 7.2.x
3. PostgreSQL 9.5(or above)


# Additional Dependencies
- Composer

# Assumptions
1. `composer install` before running the script
2. The database `testdb` exists
3. The database user (provided at commandline argument) has access to database
4. Database runs on port 5432
5. CSV file contains header row


# Users table definition
The users table is created with the following SQL:
`CREATE TABLE IF NOT EXISTS users (
    name character varying(255),
    surname character varying(255), 
    email  character varying(255) NOT NULL UNIQUE PRIMARY KEY
)`
                     
# Command Options
`php user_upload.php --help`

Running this command display the available commandline options:
Usage: user_upload.php [-c|--create_table] [-d|--dry_run] [-h|--help] [INPUT-FILE] [USER] [PASSWORD] [HOST]
  -c, --create_table    create_table - only create the table
  -d, --dry_run         dry_run - only test the input file
  -h, --help  
The first 3 (-c, -d, -h) are optional, but the others are mandatory.

php user_upload.php "users_valid.csv" --dry_run 

This command will do a dry run (validation checks) on the given csv file.
The argument specified should be a relative path. 


`php user_upload.php "users_valid.csv" --create_table tester test_password localhost`

This command will create the table `users`. Note that the input file, database user, password and host needs to be provided.


`php user_upload.php "users_valid.csv"  tester test_password localhost`

This command will insert the given csv data (if valid), into the `users` table, with the specified database user, password, and host