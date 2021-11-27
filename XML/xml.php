<?php 
    
    define("ROWNUM", 10);
    define("COLNUM", 10);

    // ATENTIE LA PATH!!!
    define("TABLEXML", "XML/tables_list.xml");
    define("GRIDXML", "XML/grids_list.xml");

    class Table
    {

        public $xcoord;
        public $ycoord;
        public $capacity;

        public function __construct($xcoord, $ycoord, $capacity)
        {
            $this->xcoord = $xcoord;
            $this->ycoord = $ycoord;
            $this->capacity = $capacity;
        }

    }

    class Cell
    {

        public $index;
        public $xcoord;
        public $ycoord;
        public $status;

        function __construct($index, $status)
        {
            $this->index = $index;

            $this->xcoord = intdiv($index, ROWNUM);
            $this->ycoord = $index % COLNUM;

            $this->status = $status;
        }

        public function __toString()
        {
            return "[" . $this->xcoord . ", " . $this->ycoord . ", " . $this->status . "]";
        }

    }

    class Booking
    {
        public $idRestaurant;
        public $indexMasa;

        function __construct($idRestaurant, $indexMasa)
        {
            $this->idRestaurant = $idRestaurant;
            $this->indexMasa = $indexMasa;
        }

        public function __toString()
        {
            return "[" . $this->idRestaurant . ", " . $this->indexMasa . "]";
        }
    }

?>

<?php

        function createNodeTable($dom, Table $table, $id)
        {
            $table_node = $dom->createElement('table');

                $attr_table_id = new DOMAttr('table_id', $id);

                $table_node->setAttributeNode($attr_table_id);


                    $child_node_xcoord = $dom->createElement('XCoord', $table->xcoord);


                $table_node->appendChild($child_node_xcoord);

                    
                    $child_node_ycoord = $dom->createElement('YCoord', $table->ycoord);


                $table_node->appendChild($child_node_ycoord);

            
                    $child_node_capacity = $dom->createElement('Capacity', $table->capacity);

                $table_node->appendChild($child_node_capacity);

            return $table_node;
        }

        function createNodeGrid($dom, Cell $cell)
        {
            $cell_node = $dom->createElement("cell");
            
                $attr_cell_index = new DOMAttr("cell_index", $cell->index);

                $cell_node->setAttributeNode($attr_cell_index);

                    $child_node_xcoord = $dom->createElement('XCoord', $cell->xcoord);

                $cell_node->appendChild($child_node_xcoord);

                    $child_node_ycoord = $dom->createElement("YCoord", $cell->ycoord);

                $cell_node->appendChild($child_node_ycoord);

                    $child_node_status = $dom->createElement('Status', $cell->status);

                $cell_node->appendChild($child_node_status);

            return $cell_node;
        }

    ?>

    <?php

        function fromArrayToXMLTable($array)
        {
            $dom = new DOMDocument();

                $dom->encoding = 'utf-8';

                $dom->xmlVersion = '1.0';

                $dom->formatOutput = true;

                $root = $dom->createElement('Tables');

                for ($i = 0; $i < count($array); $i++)
                {
                    $table = $array[$i];

                    $table_node = createNodeTable($dom, $table, $i);

                    $root->appendChild($table_node);
                }

                $dom->appendChild($root);

            $dom->save(TABLEXML);

            echo TABLEXML . " has been successfully created<br>";
        }

        function createNewGrid($email)
        {
            $username = explode("@", $email)[0];
            $gridXML = "XML/grids_" . $username . ".xml";

            $dom = new DOMDocument();
            $dom->encoding = 'utf-8';

            $dom->xmlVersion = '1.0';

            $dom->formatOutput = true;

            $root = $dom->createElement('Grids');

            $dom->appendChild($root);

            $dom->save($gridXML);
        }

        function fromXMLToArrayTable()
        {
            $xml = simplexml_load_file(TABLEXML) or die ("Error: Cannot read from XML file!");

            $list = $xml->table;

            $arr = array();

            for ($i = 0; $i < count($list); $i++)
            {
                array_push($arr, new Table($list[$i]->XCoord, $list[$i]->YCoord, $list[$i]->Capacity));
            }

            return $arr;
        }

        function fromArrayToXMLGrid($dict, $email)
        {
            if(count(array_keys($dict)) == 0)
            {
                $array = array();

                for($i = 0; $i < 100; $i++)
                {
                    array_push($array, new Cell($i, "FREE"));
                }

                array_push($dict, $array);
            }

            $dom = new DOMDocument();
            $dom->encoding = 'utf-8';

            $dom->xmlVersion = '1.0';

            $dom->formatOutput = true;

            $root = $dom->createElement('Grids');

            $username = explode("@", $email)[0];
            $gridXML = "XML/grids_" . $username . ".xml";

            foreach ($dict as $key => $value)
            {
                if ($key !== "-1")
                {
                    $restaurant = $dom->createElement("grid");
                    $restaurant->setAttribute("id", $key);

                    for ($i = 0; $i < count($value); $i++)
                    {
                        $cell = $value[$i];

                        $table_node = createNodeGrid($dom, $cell);

                        $restaurant->appendChild($table_node);
                    }
                }
            }

            $dom->appendChild($root);

            $dom->save($gridXML);

            //echo GRIDXML . " has been successfully created<br>";
        }

        function fromSingleArrayToXMLGrid($arr, $email, $restaurantId)
        {
            $username = explode("@", $email)[0];
            $gridXML = "XML/grids_" . $username . ".xml";
            $xml = simplexml_load_file($gridXML);

            $list = $xml->grid;
            $found = false;
            $pos = -1;

            for ($i = 0; $i < count($list); $i++)
            {
                if($list[$i]->attributes()->id == $restaurantId)
                {
                    $found = true;
                    $pos = $i;
                }
            }

            if ($found === FALSE)
            {
                $dom = dom_import_simplexml($xml)->ownerDocument;

                $restaurant = $dom->createElement("grid");
                $restaurant->setAttribute("id", $restaurantId);

                for ($i = 0; $i < count($arr); $i++)
                {
                    $cell = $arr[$i];

                    $table_node = createNodeGrid($dom, $cell);

                    $restaurant->appendChild($table_node);
                }

                $root = $dom->getElementsByTagName("Grids")[0];
                $root->appendChild($restaurant);
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
            }

            else if ($found === TRUE)
            {
                $dom = dom_import_simplexml($xml)->ownerDocument;
                
                $list = $xml->grid[$pos];

                for($i = 0; $i < count($list); $i++)
                {
                    //echo "<script>console.log('" . $arr[$i]->status . "');</script>";
                    //$list->cell[$i]->Status = $arr[$i]->status;
                   // $list->cell[$i]->replaceChild();
                    //echo "<script>console.log('" . $list->cell[$i]->Status . "');</script>";
                    unset($list->cell[$i]->Status);
                    //echo "<script>console.log('" . $list->cell[$i]->Status . "');</script>";
                    $list->cell[$i]->addChild("Status", $arr[$i]->status);
                    //echo "<script>console.log('" . $list->cell[$i]->Status . "');</script>";
                }
            }
            
            $dom->formatOutput = true;
            $dom->save($gridXML);
        }

        function fromXMLToArrayGrid($email, $restaurantId)
        {
            $username = explode("@", $email)[0];
            $gridXML = "XML/grids_" . $username . ".xml";

            $xml = @simplexml_load_file($gridXML);

            if ($xml === FALSE)
            {
                $arr = array();
                fromArrayToXMLGrid($arr, $email, -1);

                $xml = simplexml_load_file($gridXML);
            }

            $list = $xml->grid;

            for($j = 0; $j < count($list); $j++)
                if($list[$j]->attributes()->id == $restaurantId)
                {
                    $arr = array();

                    $list2 = $list[$j]->cell;

                    for ($i = 0; $i < count($list2); $i++)
                    {
                        array_push($arr, new Cell((int) $list2[$i]->attributes()->cell_index, $list2[$i]->Status));
                    }

                    return $arr;
                }

            return null;
        }
        
        function fromArrayToXMLBooking($array, $email)
        {
            $dom = new DOMDocument();
            $dom->encoding = 'utf-8';

            $dom->xmlVersion = '1.0';

            $dom->formatOutput = true;

            $root = $dom->createElement('Bookings');

            for($i = 0; $i < count($array); $i++)
            {
                $booking_node = createNodeBooking($dom, $array[$i], $i);

                $root->appendChild($booking_node);
            }

            $dom->appendChild($root);

            $dom->formatOutput = true;

            $username = explode("@", $email)[0];
            $bookingXML = "XML/booking_" . $username . ".xml";

            $dom->save($bookingXML);

        }

        function createNodeBooking($dom, Booking $booking, $index)
        {
            $booking_node = $dom->createElement("booking");
            
                $attr_booking_index = new DOMAttr("booking_index", $index);

                $booking_node->setAttributeNode($attr_booking_index);

                    $child_node_id = $dom->createElement('IdRestaurant', $booking->idRestaurant);

                $booking_node->appendChild($child_node_id);

                    $child_node_index = $dom->createElement("IndexMasa", $booking->indexMasa);

                $booking_node->appendChild($child_node_index);

            return $booking_node;
        }

        function fromXMLToArrayBooking($email)
        {
            $username = explode("@", $email)[0];
            $bookingXML = "XML/booking_" . $username . ".xml";

            $xml = @simplexml_load_file($bookingXML);

            $list = $xml->booking;

            $arr = array();
            for ($i = 0; $i < count($list); $i++)
            {
                array_push($arr, new Booking($list[$i]->idRestaurant, $list[$i]->indexMasa));
            }

            return $arr;
        }

        function createNewBookings($email)
        {
            $username = explode("@", $email)[0];
            $bookingXML = "XML/booking_" . $username . ".xml";

            $dom = new DOMDocument();
            $dom->encoding = 'utf-8';

            $dom->xmlVersion = '1.0';

            $dom->formatOutput = true;

            $root = $dom->createElement('Bookings');

            $dom->appendChild($root);

            $dom->save($bookingXML);
        }

    ?>

    <?php

        function createTableFromIndex($index, $capacity)
        {
            $cellRow = intdiv($index, ROWNUM);
            $cellCol = $index % COLNUM;
            
            return new Table($cellRow, $cellCol, $capacity);
        }
    ?>

<html>

    <body>

        <?php

            //$tableArr = fromXMLToArrayTable();
            //fromArrayToXMLTable($tableArr);

            //$gridArr = fromXMLToArrayGrid();
            //fromArrayToXMLGrid($gridArr);

        ?>

    </body>

</html>