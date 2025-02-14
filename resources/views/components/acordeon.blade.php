@props(['id'])
<div class="panel-group" id="{{$id}}" role="tablist" aria-multiselectable="true">
    {{$slot}}
    {{-- <div class="panel panel-default">
       <div class="panel-heading" role="tab" id="imagen">
          <h5 class="panel-title">
             <a role="button" data-toggle="collapse" class="accordion-plus-toggle collapsed" data-parent="#acordeon_imagenes" href="#content_imgs" aria-expanded="false" aria-controls="content_imgs">Evidencias</a>
          </h5>
       </div>
       <div id="content_imgs" class="panel-collapse collapse" role="tabpanel" aria-labelledby="imagen">
            <div class="container">
                <div class="row">
                    @foreach ($mante->lastMante->imagenes as $img)
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                        <div class="form-group">
                            <div class="" style="margin-bottom: 1em; margin-right: 2em;">
                                <div class="img_galery">
                                    <img class="upd_img figure-img img-fluid rounded img-thumbnail" height="100px" width="100px" src="{{asset('storage/'.$img->path)}}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
       </div>
    </div> --}}
</div>
<style>
    /* plus glyph for showing collapsible panels */
    .panel-heading .accordion-plus-toggle:before {
       font-family: FontAwesome;
       content: "\f068";
       float: right;
       color: silver;
    }
    
    .panel-heading .accordion-plus-toggle.collapsed:before {
       content: "\f067";
       color: silver;
    }
    
    /* arrow glyph for showing collapsible panels */
    .panel-heading .accordion-arrow-toggle:before {
       font-family: FontAwesome;
       content: "\f078";
       float: right;
       color: silver;
    }
    
    .panel-heading .accordion-arrow-toggle.collapsed:before {
       content: "\f054";
       color: silver;
    }
    
    /* sets the link to the width of the entire panel title */
    .panel-title > a {
       display: block;
    }
    </style>