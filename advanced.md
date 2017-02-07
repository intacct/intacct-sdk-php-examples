# Advanced Topics for PHP SDK

## Credentials
To make requests to Intacct Web Services, you will need to supply the Intacct credentials to the Intacct PHP SDK.
There are a number of ways to do this:

1. User-defined Environment Variables - see GettingStarted example.  Variables are defined in login.cfg file and `parse_ini_file()` is used to load them.
2. Default Environment Variables - for Sender Credentials, the default variables are INTACCT_SENDER_ID, INTACCT_SENDER_PASSWORD; for the Login Credentials, the default variables are INTACCT_COMPANY_ID, INTACCT_USER_ID, INTACCT_USER_PASSWORD.  You would define these variables within your OS (instead of a config or ini file) and the PHP SDK will look for them by name.
3. Default credential profile file - located at ~/.intacct/credentials.ini, this file uses PHP-style variable names.  For Sender Credentials, the default variables are sender_id, sender_password; for the Login Credentials, the default variables are company_id, user_id, user_password.
4. User-defined credential profile file - located at ~/.intacct/credentials.ini, this uses the same format as Default credential profile file, but defines profiles using INTACCT_COMPANY_PROFILE for each profile instead of just \[default]

Whichever method you choose will depend on your situation.  For example, you may use a combination of two of these methods (e.g. Default Environment Variables for your sender credentials and User-defined Environment Variables for each of your company login credentials if you have multiple companies).  Keep in mind, no matter how you implement your login, it is most important to safely secure the credentials.

## Custom Objects

## Adding Functions

## Logging