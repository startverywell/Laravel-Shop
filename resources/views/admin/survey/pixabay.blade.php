<?php
    $div1 = "<div class='image-column'>";
    $div2 = "<div class='image-column'>";
    $flag = true;
    foreach ($pixa_photo as $key => $value){
        if($flag){
            $div1 .= '
            <div class="image-container" data-zoom=0 data-left=0>
                <img loading="lazy" src="'.$value->imageURL.'" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
                <div class="image-overlay">
                    <a target="_blank" href="'.$value->pageURL.'">'.$value->user.'</a>
                </div>    
            </div>
            ';
            $flag = false;
        } else {
            $div2 .= '
            <div class="image-container" data-zoom=0 data-left=1>
                <img loading="lazy" src="'.$value->imageURL.'" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
                <div class="image-overlay">
                    <a target="_blank" href="'.$value->pageURL.'">'.$value->user.'</a>
                </div>    
            </div>
            ';
            $flag = true;
        }
    }
    $div1 .= '</div>';
    $div2 .= '</div>';
    echo $div1;
    echo $div2;
?>