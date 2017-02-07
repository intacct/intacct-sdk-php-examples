# Query Client Example

## Prerequisites
 
- You have successfully ran the GettingStarted example and understand what it is doing.
- You have a project where you can run the Query Client Example and Intacct is in your vendor directory in that project. 

## Run the GettingStarted example

1. Copy [QueryClientExample.php](./QueryClientExample.php) and create a directory [.intacct] and put [credentials.ini](credentials.ini) into your IDE project.
   
1. Update the `credentials.ini` with your login credentials. Here we are demonstrating another way to pass in the credentials.

1. Run the program and observe the results.

   You get a listing of the number of APBILL objects in your company.  If you have at least 1 ABPILL, you will see the date it was created, the amount posted, and the Vendor name.
   
### What's next?

- Try out an example that focuses on logging.