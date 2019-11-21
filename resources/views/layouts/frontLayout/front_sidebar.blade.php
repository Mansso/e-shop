<div class="left-sidebar">
    <h2>Category</h2>
    <div class="panel-group category-products" id="accordian">

        <!--category-products-->
        <div class="panel panel-default">
            <?php //echo $categories_menu; 
            ?>
            @foreach($categories as $cat)
            <div class="panel-heading">
                <h4 class="panel-heading">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordian" href="#{{ $cat->id }}">
                        <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                        {{ $cat->name }}
                    </a>
                </h4>
            </div>
            <div id="{{ $cat->id }}" class="panel-collapse collapse" style="height: 0px;">
                <div class="panel-body">
                    <ul style="padding-left:40px;margin:-20px 0 -15px;">
                        @foreach($cat->categories as $subcat)
                        @if($subcat->status==1)
                        <li><a href="{{ asset('/products/'.$subcat->url) }}">{{ $subcat->name }}</a></li>
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!--/category-products-->


</div>