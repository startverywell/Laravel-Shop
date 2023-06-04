<div id="All" class="tabcontent active">
    <div class="card example-1 square scrollbar-dusty-grass square thin" id="card_image_view_all" onscroll="imageScroll('all')">
        <div class="images-wrapper" id="card_image_view_all_view">
        <?php
            $div1 = "<div class='image-column'>";
            $div2 = "<div class='image-column'>";
            foreach ($photo as $key => $value){
                $div1 .= '
                <div class="image-container" data-zoom=0 data-left=0 onclick="imageClick(this)">
                    <img loading="lazy" src="'.$value->urls->small.'" alt="'.$value->alt_description.'" data-download="'.$value->links->download_location.'?client_id='.env('UNSPLASH_ACCESS_KEY').'" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
                    <div class="image-overlay">
                        <a href="'.$value->links->html.'?utm_source=Formstylee&utm_medium=referral">'.$value->user->name.'</a>
                    </div>    
                </div>
                ';
            }
            foreach ($pixa_photo as $key => $value){
                $div2 .= '
                <div class="image-container" data-zoom=0 data-left=1 onclick="imageClick(this)">
                    <img loading="lazy" src="'.$value->imageURL.'" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
                    <div class="image-overlay">
                        <a href="'.$value->pageURL.'">'.$value->user.'</a>
                    </div>    
                </div>
                ';
            }
            $div1 .= '</div>';
            $div2 .= '</div>';
            echo $div1;
            echo $div2;
        ?>
        </div>
    </div>
</div>
<div id="Unsplash" class="tabcontent">
    <div class="card example-1 square scrollbar-dusty-grass square thin" id="card_image_view_unsplash" onscroll="imageScroll('unsplash')">
        <div class="images-wrapper" id="card_image_view_unsplash_view">
        <?php
            $div1 = "<div class='image-column'>";
            $div2 = "<div class='image-column'>";
            $flag = true;
            foreach ($photo as $key => $value){
                if($flag){
                    $div1 .= '
                    <div class="image-container" data-zoom=0 data-left=0 onclick="imageClick(this)">
                        <img loading="lazy" src="'.$value->urls->small.'" alt="'.$value->alt_description.'" data-download="'.$value->links->download_location.'?client_id='.env('UNSPLASH_ACCESS_KEY').'" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
                        <div class="image-overlay">
                            <a target="_blank" href="'.$value->links->html.'?utm_source=Formstylee&utm_medium=referral">'.$value->user->name.'</a>
                        </div>    
                    </div>
                    ';
                    $flag = false;
                } else {
                    $div2 .= '
                    <div class="image-container" data-zoom=0 data-left=1 onclick="imageClick(this)">
                        <img loading="lazy" src="'.$value->urls->small.'" alt="'.$value->alt_description.'" data-download="'.$value->links->download_location.'?client_id='.env('UNSPLASH_ACCESS_KEY').'" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
                        <div class="image-overlay">
                            <a target="_blank" href="'.$value->links->html.'?utm_source=Formstylee&utm_medium=referral">'.$value->user->name.'</a>
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
        </div>
    </div> 
</div>
<div id="Pixabay" class="tabcontent">
    <div class="card example-1 square scrollbar-dusty-grass square thin" id="card_image_view_pixabay" onscroll="imageScroll('pixabay')">
        <div class="images-wrapper" id="card_image_view_pixabay_view">
        <?php
            $div1 = "<div class='image-column'>";
            $div2 = "<div class='image-column'>";
            $flag = true;
            foreach ($pixa_photo as $key => $value){
                if($flag){
                    $div1 .= '
                    <div class="image-container" data-zoom=0 data-left=0 onclick="imageClick(this)">
                        <img loading="lazy" src="'.$value->imageURL.'" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
                        <div class="image-overlay">
                            <a target="_blank" href="'.$value->pageURL.'">'.$value->user.'</a>
                        </div>    
                    </div>
                    ';
                    $flag = false;
                } else {
                    $div2 .= '
                    <div class="image-container" data-zoom=0 data-left=1 onclick="imageClick(this)">
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
        </div>
    </div>
</div>