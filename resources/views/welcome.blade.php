@extends('layouts.app')

@section('content')

<div class="brand-logo center">
        <img src="{{ URL::asset('images/eSmartBuy.png') }}">
    </div>

    <div class="container disc">
        <p>Find your best product and compare prices from different websites</p>
    </div>

    <div class="container">
        <form action="/search" method="GET">
          {{ csrf_field() }}
          <div class="input-group mb-2">
              <input type="text" id="product" class="form-control{{ $errors->has('product') ? ' is-invalid' : '' }}" name="product" placeholder="Search your item" aria-label="Search your products..." aria-describedby="button-addon2">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2"><i class="fas fa-search text-grey"
                  aria-hidden="true"></i></button>
              </div>
          </div>
          @if ($errors->has('product'))
            <span class="invalid-feedback d-block mb-3 mt-0" role="alert">
                <strong>{{ $errors->first('product') }}</strong>
            </span>
          @endif
        </form>
    </div>

    <div class="container ts">
        <p>Top searches today</p>
    </div>
    
    <div class="container">
        <div class="row">
            <?php if(!empty($results)): ?>
                <?php foreach($results as $result): ?>
            
                <div class="col-2 box">
                    <div class="inner"><a class="search_item" href="/product/{{ $result->id }}"><?php echo $result->search_query; ?> <span class="badge badge-light"><?php echo $result->count; ?></span></a></div>
                </div>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

@endsection