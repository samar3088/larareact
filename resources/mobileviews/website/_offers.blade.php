
    <div class="flash-sale-wrapper pb-3">
        <div class="container">
            <div class="section-heading d-flex align-items-center justify-content-between">
                <div class="col-12 headtitle">
                    <h3 class="btn btn-primary btn-sm p" href="#">Exclusive offers only for you</h3>
                    <div class="linedivider"> </div>
                    <div class="smalldivider"></div>
                </div>
           </div>
           <div class="owl-carousel owl-theme datastest">

                @foreach ($homesliders as $homeslider)
                    <div class="flash-sale-card"  >
                        <div class="card card-pop">
                            <div class="card-body">
                                <span class="iw pull-right " style="float:right;">
                                    <a target="_blank" href="{{ $homeslider->buttonlink }}" title="">
                                        <img style="float:right;" src="{{Storage::url($homeslider->file) }}" width="100%" alt=""/>
                                    </a>
                                </span>
                                <div class="pull-left contentss">
                                    <a style="color:#d52a33" href="{{ $homeslider->buttonlink }}" href="" title="">
                                        <span class="card-title vtitle mBtm-10">{!! $homeslider->heading !!}</span>
                                    </a>
                                    <div class="card-text blog-excerpt mBtm-5">{{ $homeslider->subheading }}</div>
                                    <span class="blog-date"> {{ $homeslider->buttontext }} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
            </div>
        </div>
    </div>
