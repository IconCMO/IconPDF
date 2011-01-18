IconPDF
=======


by [Icon Systems, Inc.](http://iconcmo.com)
-------------------------------------------

IconPDF is a wrapper to implement PDFlib ([http://pdflib.com/](http://pdflib.com)) functions in TCPDF ([http://www.tcpdf.org/](http://www.tcpdf.org)). To begin with, very few PDFlib functions are supported, as they covered our initial needs. Additionally, it was built with the goal of implementing the PDFlib v6 (or so) interface, rather than the more current APIs. More than likely, you will need to write further code to be suitable to your project.

To use, copy the IconPDF.php file into your application. Open it, and adjust the paths to TCPDF as appropriate. Then, in your PHP code that calls PDFlib, add a line to include IconPDF.php. Then, give it a shot and see how it works!

*NOTE*: PDFlib will always take precedence if it is installed. To use IconPDF, you'll need to uninstall PDFlib.

*NOTE 2*: As specified in the license, Icon Systems, Inc. makes no warranty to the suitability of this code to any particular purpose. In addition, we offer no support or guarantee of further development. You've been warned! But, if you'd like to contribute, please feel free to do so!
