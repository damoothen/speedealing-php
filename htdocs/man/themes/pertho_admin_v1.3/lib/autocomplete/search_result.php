<?php
    $sParam = $_POST[search_item];
    $doc = new DOMDocument();
    $doc->load( 'data.xml' );
    $items = $doc->getElementsByTagName( "item" );
    if($items > 0 ){
        foreach( $items as $item ) {
            $value = $item->nodeValue;
            if ( strtolower($value) == strtolower($sParam) ) {
               $valuefounded = true ;
            }
        }
    }
?>

<div class="search_results search_pop">
	<?php if ( $valuefounded == true ) { ?>
        <h5 class="sepH_b">Showing 4 results for <?php echo $sParam; ?></h5>
        <ol>
            <li>
                <a href="#">Article 1 title</a>
                <p>lorem ipsum vulputate turpis etiam placerat aliquam quam vulputate non, nostra fermentum volutpat neque consequat in <mark><?php echo $sParam;?></mark> morbi vehicula maecenas, consequat vulputate etiam quisque non suspendisse neque suscipit. ..</p>
            </li>
            <li>
                <a href="#">Article 2 title</a>
                <p>lorem ipsum rutrum conubia elementum <mark><?php echo $sParam;?></mark> morbi integer, massa fames maecenas consectetur...</p>
            </li>
            <li>
                <a href="#">Article 3 title</a>
                <p>lorem ipsum senectus magna, consequat curae pharetra tortor, mauris <mark><?php echo $sParam;?></mark> aliquam...</p>
            </li>
            <li>
                <a href="#">Article 4 title</a>
                <p>lorem ipsum venenatis vulputate cubilia <mark><?php echo $sParam;?></mark> scelerisque ante luctus nulla ipsum, sagittis nec eros rhoncus porta luctus pharetra dolor mattis elementum, aenean maecenas malesuada ipsum pretium sit etiam bibendum. ..</p>
            </li>
        </ol>
	<?php } else { ?>
        Sorry no matches for <strong><?php echo $sParam;?></strong>, please try some different term.
    <?php }; ?>
</div>
