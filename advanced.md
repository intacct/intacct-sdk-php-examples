# Advanced Topics for PHP SDK

## Logging
We suggest using [Monolog](https://seldaek.github.io/monolog/) for your logging because it's what is being used in the Intacct PHP SDK (but at the very least, we recommend using a PSR7 interface).  

Take a look at the LoggingExample.  In order to run this, you need to add the following to your composer.json 
```json
"require-dev": {
     "monolog/monolog": "1.21.0"
   }
   ```
If you are using Monolog in your own code, select a handler from the list of handlers.
Lastly, pass your logger into the IntacctClient. You can set the log level as a parameter as well.  The default logging level for the Intacct PHP SDK is DEBUG.

## Custom Objects
If you want to create your own custom objects, we recommend you do the following:

1. Create an abstract class that extends the AbstractFunction.  
2. Place all your fields for your object into your abstract object.
3. Create your concrete class(es) that extend your abstract class. 
4. In your concrete classes, implement the writeXml() function (which will actually write the XML for your object).

For example, AbstractBill extends AbstractFunction.  The BillCreate and BillDelete are the classes that realize the AbstractBill and write the XML for creating or deleting a bill.
