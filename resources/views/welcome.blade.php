@extends('layouts.app')

@section('content')

<div class="brand-logo center">
        <h1 style="text-align: center;">Market Analysis and Least Price Recommendation System</h1>
    </div>

    <div class="container disc">
        <p>Find your best product and compare prices from different websites</p>
    </div>

    <div class="container">
        <form action="/search" method="GET">
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

    @if(!empty($recommended_search_query))

    <div class="container ts">
        <p>You may want to search for:</p>
    </div>

    <div class="container" style="margin-bottom: 20px;">
        <div class="row">
            <?php $i = 0; ?>
            <?php foreach($recommended_search_query as $recommend): ?>
                <div class="col-2 box">
                    <div class="inner"><?php echo $recommend[0]; ?></div>
                </div>
                <?php $i++; ?>

                <?php if($i == 4) {
                    break;
                } ?>
            <?php endforeach; ?>
        </div>
    </div>

    @endif

    @if(!empty($top_searches_today))

    <div class="container ts">
        <p style="">Top searches today</p>
    </div>
    
    <div class="container">
        <div class="row">
            <?php $i = 0; ?>
            <?php foreach($top_searches_today as $result): ?>

                <?php foreach($result as $re): ?>

                <div class="col-2 box">
                    <div class="inner"><a class="search_item" href="/product/{{ $re['id'] }}"><?php echo $re['search_query']; ?> <span class="badge badge-light"><?php echo $re['count']; ?></span></a></div>
                </div>

                <?php endforeach; ?>

                <?php $i++; ?>

                <?php if($i == 15) {
                    break;
                } ?>

            <?php endforeach; ?>
        </div>
    </div>

    @endif

@endsection