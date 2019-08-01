<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>eSmart Buy</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('js/app.js') }}">
</head>
<body>
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
            <p>Top searches this week</p>
        </div>
    
        <div class="container">
          <div class="row">
            <?php foreach($results as $result): ?>
            <?php $i = 0; ?>
            <div class="col-2 box">
                  <div class="inner"><a id="#item{{ $i }}" class="search_item" href="#"><?php echo $result->search_query; ?> <span class="badge badge-light"><?php echo $result->count; ?></span></a></div>
            </div>
            <?php $i++; ?>

            <?php endforeach; ?>
          </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script>
        $(function() {
          $('')
        });
    </script>
</body>
</html>