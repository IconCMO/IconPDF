<?php

/***********************************************************************
 * IconPDF (c) 2011 Icon Systems, Inc. http://iconcmo.com
 *
 * See license file for more details.
 *
 ***********************************************************************
 *
 * IconPDF is a thin wrapper around TCPDF that replaces PDFlib with a free solution.
 *
 * It is not even remotely feature complete, but implements the subset of PDFlib functions
 * that we required. Additionally, some of the functions implemented here were for
 * PDFlib version <6, and have been deprecated in current PDFlibs.
 *
 ***********************************************************************/


@include_once( 'tcpdf/config/lang/eng.php');
@include_once( 'tcpdf/tcpdf.php');

if ( !function_exists( "PDF_new" ) ) { // only create subclass if PDFLib not installed
    class IconPDF extends TCPDF {

        public $graphicsX = 0;
        public $graphicsY = 0;
        public $textX = 0;
        public $textY = 0;
        public $fontsize = 0;
        public $pageHeight = 0;
        public $pageWidth = 0;
        public $leading = 0;

        function __construct() {
            parent::__construct( "p","pt","letter",true, true );
            // this has to be done to remove the default cell margin
            $this->cMargin = 0;
            $this->setViewerPreferences( array( "PrintScaling"=>"None" ) );
        }

        function Header() {
            // no headers
        }
        function Footer() {
            // no footers
        }

        public function getFontAscent() {
            return $this->FontAscent;
        }

        public function getLineWidth() {
            return $this->LineWidth;
        }

        // change the defaults for the Cell function to help things align properly; depends on the use of Cell in class TCPDF
        public function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='A', $valign='T') {
            parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign);
        }
    }
}

if ( !function_exists( "PDF_new" ) ) {
    function PDF_new() {
        return new IconPDF();
    }
}
if ( !function_exists( "PDF_delete" ) ) {
    function PDF_delete($pdf) {
        unset( $pdf );
    }
}
if ( !function_exists( "PDF_begin_page" ) ) {
    function PDF_begin_page($pdf, $width, $height) {
        $orientation = "P"; // portrait is fine; TCPDF will change orientation as necessary
        $pdf->AddPage($orientation, array( $width, $height ) );
        $pdf->SetMargins( 0, 0, 0 );
        $pdf->SetHeaderMargin( 0 );
        $pdf->SetFooterMargin( 0 );
        $pdf->SetAutoPageBreak( false );
        $pdf->SetLineWidth( 1 );
        $pdf->pageHeight = $height;
        $pdf->pageWidth = $width;
    }
}
if ( !function_exists( "PDF_end_page" ) ) {
    function PDF_end_page() {
    }
}
if ( !function_exists( "PDF_open_image_file" ) ) {
    function PDF_open_image_file($pdf, $format, $filename) {
        return $filename;
    }
}
if ( !function_exists( "PDF_close_image" ) ) {
    function PDF_close_image() {
    }
}
if ( !function_exists( "PDF_fit_image" ) ) {
    function PDF_fit_image($pdf, $image, $x, $y, $options) {
        preg_match_all("/boxsize {(\d*) (\d*)}/", $options, $results);
        $width = $results[1][0];
        $height = $results[2][0];
        $file = str_replace(' ', '%20', $image); // encode spaces as necessary
        $imsize = @getimagesize($file);
        // get original image width and height in pixels
        list($originalWidth, $originalHeight) = $imsize;
        // TCPDF uses the upper left while TCPDF uses the lower left to position the image.
        // But, we haven't been given the resized dimensions of the image to know where to 
        // place the top left of the resized image. Thus, we calculate it.
        // We know both the $height and $width of the box that we'll be placing the image in,
        // and we know the original (pixel) dimensions of the image.
        // Algorithm:
        //  - If the proportions of the image after resizing are wider than it is tall...
        //  - then we can calculate the height of the resized image from the (known) width
        //    of the resized image and the original proportions.
        //  - This height can be used for both the positioning *and* the resizing.
        if ( $originalWidth > ( $originalHeight * $width/$height ) ) {
            $height = $width * $originalHeight/$originalWidth;
        }
        $pdf->Image( $image, $x, $pdf->getPageHeight() - $y - $height, $width, $height, '','','T',2,300,'',false,false,0,true,false );
    }
}
if ( !function_exists( "PDF_open_file" ) ) {
    function PDF_open_file() {
        return 1;
    }
}
if ( !function_exists( "PDF_close" ) ) {
    function PDF_close() {
    }
}
if ( !function_exists( "PDF_get_buffer" ) ) {
    function PDF_get_buffer($pdf) {
        return $pdf->Output('', 'S');
    }
}
if ( !function_exists( "PDF_load_font" ) ) {
    function PDF_load_font($pdf, $name, $encoding, $options) {
        $fontMap = array(
            "Times-Roman"=>array("name"=>"times","style"=>""),
            "Times-Bold"=>array("name"=>"timesb","style"=>"b"),
            "Times-Italic"=>array("name"=>"timesi","style"=>"i"),
            "Times-BoldItalic"=>array("name"=>"timesbi","style"=>"bi"),
            "Helvetica"=>array("name"=>"helvetica","style"=>""),
            "Helvetica-Bold"=>array("name"=>"helveticab","style"=>"b"),
            "Helvetica-Oblique"=>array("name"=>"helveticai","style"=>"i"),
            "Helvetica-BoldOblique"=>array("name"=>"helveticabi","style"=>"bi"),
            "Courier"=>array("name"=>"courier","style"=>""),
            "Courier-Bold"=>array("name"=>"courierb","style"=>"b"),
            "Courier-Oblique"=>array("name"=>"courieri","style"=>"i"),
            "Courier-BoldOblique"=>array("name"=>"courierbi","style"=>"bi"),
            "Symbol"=>array("name"=>"symbol","style"=>""),
            "ZapfDingbats"=>array("name"=>"zapfdingbats","style"=>"") );
        $fontMap = array(
            "Times-Roman"=>array("name"=>"times","style"=>""),
            "Times-Bold"=>array("name"=>"times","style"=>"b"),
            "Times-Italic"=>array("name"=>"times","style"=>"i"),
            "Times-BoldItalic"=>array("name"=>"times","style"=>"bi"),
            "Helvetica"=>array("name"=>"helvetica","style"=>""),
            "Helvetica-Bold"=>array("name"=>"helvetica","style"=>"b"),
            "Helvetica-Oblique"=>array("name"=>"helvetica","style"=>"i"),
            "Helvetica-BoldOblique"=>array("name"=>"helvetica","style"=>"bi"),
            "Courier"=>array("name"=>"courier","style"=>""),
            "Courier-Bold"=>array("name"=>"courier","style"=>"b"),
            "Courier-Oblique"=>array("name"=>"courier","style"=>"i"),
            "Courier-BoldOblique"=>array("name"=>"courier","style"=>"bi"),
            "Symbol"=>array("name"=>"symbol","style"=>""),
            "ZapfDingbats"=>array("name"=>"zapfdingbats","style"=>"") );
        if ( array_key_exists($name, $fontMap) ) {
            $fontname = $fontMap[$name]["name"];
            $style = $fontMap[$name]["style"];
        } else {
            error_log( "***** PDF font $name does not exist!" );
            $fontname = "helvetica";
            $style = "none";
        }
        return array( "font"=>$fontname, "style"=>strtoupper( $style ), "encoding"=>$encoding, "options"=>$options );
    }
}
if ( !function_exists( "PDF_setfont" ) ) {
    function PDF_setfont( $pdf, $font, $size ) {
        $pdf->SetFont( $font["font"], $font["style"], $size );
    }
}
if ( !function_exists( "PDF_set_text_pos" ) ) {
    function PDF_set_text_pos( $pdf, $x, $y ) {
        $dimensions = $pdf->getPageDimensions();
        $pdf->textX = $x;
        $pdf->textY = $pdf->getPageHeight() - $y - $pdf->getFontSizePt();
    }
}
if ( !function_exists( "PDF_show" ) ) {
    function PDF_show( $pdf, $text ) {
        $pdf->SetXY( $pdf->textX, $pdf->textY );
        $pdf->write( 0, $text );
    }
}
if ( !function_exists( "PDF_continue_text" ) ) {
    function PDF_continue_text( $pdf, $text ) {
        $pdf->textY += $pdf->leading;
        $pdf->SetXY( $pdf->textX, $pdf->textY );
        $pdf->write( 0, $text );
    }
}
if ( !function_exists( "PDF_set_value" ) ) {
    function PDF_set_value( $pdf, $valueToSet, $parameter ) {
        switch ($valueToSet) {
        case 'leading':
            $pdf->leading = $parameter;
            break;
        default:
            error_log( "Trying to set unknown parameter $valueToSet to $parameter.");
            break;
        }
    }
}
if ( !function_exists( "PDF_get_value" ) ) {
    function PDF_get_value( $pdf, $valueToGet, $parameter ) {
        switch (strtolower($valueToGet)) {
        case 'textx':
            return $pdf->textX;
            break;
        case 'texty':
            return $pdf->getPageHeight() - $pdf->textY - $pdf->getFontSizePt();
            break;
        default:
            error_log( "******** $valueToGet not supported in " . __FUNCTION__ );
            return null;
            break;
        }
    }
}
if ( !function_exists( "PDF_show_boxed" ) ) {
    function PDF_show_boxed($pdf, $text, $x, $y, $width, $height, $mode, $feature) {
        $align = strtoupper( $mode );
        $align= $align[0];
        if ( !in_array($align, array( 'L','R','C','J') ) ) {
            logger( "Unsupported text alignment." );
            $align = 'L';
        }
        // this math, matched with the 'T' and 'T' options to Cell, makes the output match PDFlib exactly
        $y += $height - $pdf->getFontSizePt();
        $y = $pdf->getPageHeight() - $y - $pdf->getFontAscent() - ( $pdf->getLineWidth() / 2 );
        $pdf->SetXY($x, $y );
        $border = 0; // 0 = no border, 1 = border (other options, see docs)
        $ln = 2; // 0 = go right after call, 1 = beginning of next line, 2 = below
        $pdf->StartTransform();
        $pdf->Rect($x, $y, $width+1, $height, 'CNZ'); // Draw clipping rectangle to match html cell.
        $pdf->Cell($width,$height,$text,$border,$ln,$align, 0, '', 0, true, 'T', 'T');
        $pdf->StopTransform();
    }
}
if ( !function_exists( "PDF_set_parameter" ) ) {
    function PDF_set_parameter() {
    }
}
if ( !function_exists( "PDF_lineto" ) ) {
    function PDF_lineto( $pdf, $x, $y ) {
        $pdf->SetXY($pdf->graphicsX, $pdf->graphicsY );
        $pdf->SetLineStyle(array("width"=>1.0));
        $pdf->Line( $pdf->getX(), $pdf->getY(), $x, $pdf->getPageHeight() - $y );
    }
}
if ( !function_exists( "PDF_moveto" ) ) {
    function PDF_moveto( $pdf, $x, $y) {
        $pdf->graphicsX = $x;
        $pdf->graphicsY = $pdf->getPageHeight() - $y;
    }
}
if ( !function_exists( "PDF_rect" ) ) {
    function PDF_rect($pdf, $x, $y, $width, $height) {
        $pdf->SetXY($pdf->graphicsX, $pdf->graphicsY );
        $pdf->SetLineStyle(array("width"=>1.0));
        $pdf->Rect( $x, $pdf->getPageHeight() - $y - $height, $width, $height );
    }
}
if ( !function_exists( "PDF_stroke" ) ) {
    function PDF_stroke() {
    }
}
if ( !function_exists( "PDF_setcolor" ) ) {
    function PDF_setcolor( $pdf, $fill, $scheme, $c1, $c2, $c3, $c4 ) {
        if ( $fill == "both" ) {
            $fill = "fillstroke";
        }
        if ( strpos( $fill, "fill" ) !== false ) {
            $pdf->SetFillColor( $c1*255, $c2*255, $c3*255 );
        }
        if ( strpos( $fill, "stroke" ) !== false ) {
            $pdf->SetTextColor( $c1*255, $c2*255, $c3*255 );
            $pdf->SetDrawColor( $c1*255, $c2*255, $c3*255 );
        }
    }
}
if ( !function_exists( "PDF_get_errmsg" ) ) {
    function PDF_get_errmsg() {
    }
}
if ( !function_exists( "PDF_set_info" ) ) {
    function PDF_set_info( $pdf, $parameter, $value ) {
        switch ( strtolower($parameter) ) {
        case "author":
            $pdf->SetAuthor( $value );
            break;
        case "subject":
            $pdf->SetSubject( $value );
            break;
        case "title":
            $pdf->SetTitle( $value );
            break;
        case "creator":
            $pdf->SetCreator( $value );
            break;
        case "keywords":
            $pdf->SetKeywords( $value );
            break;
        default:
            error_log( "Cannot set PDF info for $parameter = $value.");
        }
    }
}
if ( !function_exists( "PDF_closepath_stroke" ) ) {
    function pdf_closepath_stroke() {
    }
}

?>
