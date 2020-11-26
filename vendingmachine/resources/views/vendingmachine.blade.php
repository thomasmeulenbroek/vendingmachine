<!DOCTYPE html>
<html>

<head>
    <title>VendingMachine</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="row">
            <h1> Vending Machine </h1>
        </div>
        <div class="row">
            <div class="col">
                @isset($change)
                @foreach($change as $item)
                <li>
                    {{ $item }} <br>
                </li>
                @endforeach
                @endisset
                <form method="post">
            </div>

            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Select</th>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <input type="radio" id="{{ $product['product_name'] }}" name="product"
                                    value="{{ $product['product_name'] }}" required />
                            </td>
                            <td>
                                <label for="{{ $product['product_name'] }}">{{ $product['product_name'] }}</label>
                            </td>
                            <td>
                                <label for="{{ $product['product_name'] }}">
                                    €{{ number_format($product['price'],2) }}</label>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                </col>
            </div>
            <div class="col">
                @foreach($valid_coins as $key=>$coin)
                <div id="{{ $coin }}">€{{ number_format($coin/100, 2) }}</div>
                <input class="coins" name="coin{{ $coin }}" type=number min=0 value="0" id="{{ $coin }}" required /><br>
                @endforeach
                <br><input type="submit" value="Buy" /><br>
                </form>
            </div>
            <div class="col">
                <tr>
                    <div>Total: &euro;</div>
                    <div id="totalPrice">0.00</div>
                </tr>
            </div>
        </div>
</body>
<script>
//Calculate total price based on coin input
function calcTotal() {
    let total = 0

    $(".coins").each(function(index) {
        let coin = this.id
        let amount = this.value
        if (amount < 0) {
            amount = amount * -1
        }
        let price = coin * amount
        total += price
    });

    $('#totalPrice').text((total / 100).toFixed(2))
}

//Add onclick listener to update total price
$('.coins').on('click', () => {
    calcTotal()
})
</script>

</html>