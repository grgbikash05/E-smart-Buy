@extends('layouts.app')

@section('content')

    <div class="container">
        <form action="/search" method="GET">
          <div class="input-group mb-2">
              <input value="<?php echo isset($_GET['product']) ? $_GET['product'] : ''; ?>" type="text" id="product" class="form-control{{ $errors->has('product') ? ' is-invalid' : '' }}" name="product" placeholder="Search your item" aria-label="Search your products..." aria-describedby="button-addon2">
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


<section class="section--product-results">
    <div class="container">
        @if(!empty($results))
        <?php foreach($results as $result): ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="product--image">
                        <img class="img-fluid" src="<?php echo $result->image; ?>" alt="">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="product--detail">
                        <h3 class="product--title"><?php echo $result->title; ?></h3>

                        <div class="site--logo">
                            <img src="{{ $result->site }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="product--specific-detail">
                        <div class="product-price">Rs. <?php echo $result->price; ?></div>

                        <a target="_blank" href="<?php echo $result->link; ?>" class="btn btn-primary">View Deal</a>
                    </div>
                </div>
            </div>

        <?php endforeach; ?> 
        
        @else

        <div class="row">
            <div class="col-md-12">
                <div class="text-danger">No products found.</div>
            </div>
        </div>

        @endif
    </div>
</section>

@endsection