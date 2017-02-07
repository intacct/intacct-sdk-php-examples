#PHP SDK Overview
What is the PHP SDK?  It is Intacct's library in PHP where you can make your requests and receive responses from Intacct's Web Service api all in PHP!

Why use the PHP SDK?  Because who likes to write XML instead of PHP? No one!  Now you can use the SDK to write your tests as well.  XML is abstracted away and you can work directly with PHP objects to make your request and parse responses.

How do I get started?  There's a Getting Started guide, but if you want more details, read on below.
##Structure
Basically you use the clients to make requests and receive responses.  It's that simple!
###Clients
There are two options for clients: `IntacctClient` and `QueryClient`.  Both clients take an optional set of parameters which can contain profile credentials (see Credentials) as well as other parameters (see `AbstractClient` for more details).
If no credential parameters are provided, the clients will default to environment variables if available (See [Advanced](advanced.md) for more information on credentials). During instantiation, the clients will execute a getAPISession request to login into Intacct to get an active session.
After the client has successfully returned a session, the client can be used to execute requests both synchronously and asynchronously.  See the constructor for acceptable parameters/overrides.
####IntacctClient
The `IntacctClient` is the client you use to execute all requests.  These requests are bundled in a `Content` (See `Content` for more information).
####QueryClient
The `QueryClient` only handles query requests. This client handles the work of performing the api call `readByQuery` and all the subsequent `readMore` functions returning the entire set of records for a given query up to the `$maxTotalCount`.
Why does this matter?  Well it takes away the burden from you of making a request for a readByQuery and then having to call readMore for additional page records.  See the constructor documentation for acceptable parameters.
###Request
All implementations for the `FunctionInterface` are found in the Functions directory.  The explicit functions are grouped under their respective feature, while the implicit functions are all located under the Common directory.
To make a request through the `IntacctClient`, first create an instance of the function you want to call (i.e. anything that implements the `FunctionInterface`). Next, wrap your function inside a `Content` and pass that through the execute method along with any other parameters you need to set.
See `execute` method for acceptable parameters.
Behind the scenes, the `AbstractClient` will convert all the provided information into XML and make the api call to Intacct.
###Response
After an `execute` method is returned, a `SynchronousResponse` is available. For requests where you have transactional as true, you should check the status in the Result to make sure the function you called executed correctly.  You can check that all functions executed successfully through the call:
 `$response->getOperation()->getResult()->ensureStatusSuccess()`
This is especially good to know if you have several `FunctionInterface`s in your `Content` and you want to make sure they all completed successfully (e.g. no abort or failure status).

Assuming you have a successful status for your query type of requests, all the data for your request is found through calling: 
`$response->getOperation()->getResult()->getData()`  You can iterate though the records to get the record data as XML objects (i.e. `SimpleXMLIterator`).  Hint: use `print_r` to see the XML in an array structure.  

With these XML objects you can get the data directly (as accessed through an array, see QueryClient example), convert to json, or parse into your own PHP objects (beyond the scope of our current SDK, but you are encouraged to do it yourself and share your work!).


###Error Handling
There are several categories of errors that can occur.  These involve the following, the types of exceptions that would be thrown, and an example accordingly:
* Sender Login (ResultException)--e.g. incorrect sender id or sender password
* User Login (OperationException)--e.g. incorrect user id, user password, company id, or session id
* Missing data/argument (InvalidArgumentException)--e.g. required parameter not provided for a request
* Business Logic (ResultException)--e.g. invalid query structure, transactional error and roll back occurs
(note: no data is returned if there's an error)
##Credentials
