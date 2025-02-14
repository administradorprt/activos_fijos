@props(['id','title','parentID','hrefID'])
<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="{{$id}}">
      <h4 class="panel-title">
        <a class="accordion-plus-toggle collapsed" role="button" data-toggle="collapse" data-parent="#{{$parentID}}" href="#{{$hrefID}}" aria-expanded="false" aria-controls="{{$hrefID}}">
          {{$title}}
        </a>
      </h4>
    </div>
    <div id="{{$hrefID}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{$id}}">
      <div class="panel-body">
        {{$slot}}
      </div>
    </div>
  </div>
 