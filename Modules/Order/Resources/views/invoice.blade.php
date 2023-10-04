<!DOCTYPE html>
<html>
<head>
<title>Invoice</title>
<style>


.column {
  float: left;
  width: 33.33%;
  padding: 5px;
}

body
{
border: 1px solid black;
  margin: 25px auto;
}

.row::after {
  content: "";
  clear: both;
  display: table;
}
h3
{
 font-size: 25px;
}
td {
  padding: 10px;
  border-bottom: 1px solid #000;
}
td.ex1 {
  
  padding-left: 10em;
}
p.ex1 {

  padding-left: 58em;
}
p.ex2 {

  padding-left: 62em;
}
table {
  width: 100%;
  border-collapse: collapse;
}
td.ex3 {
  
  font-size: 25px;
}
.div1 {
     

  width: 300px;
  padding-top: 15px;
  padding-bottom: 15px;
  outline-style: dotted;
}

</style>
</head>
<body>

<h3><center>Invoice</center></h3>



<table>
  <tbody>
    <tr>
      <td style="width: 70%; border-bottom: none;"><p><strong>Sold By: {{$order->shop->name}}.</strong><br><br></td>
      <td style=" border-bottom: none; text-align: center;"><div class="div1">Invoice Number # {{$order->invoice_no}}</div></td>
    </tr>
  </tbody>
</table>

</p>
<hr>
<div class="row">
  <div class="column">
   <p><strong>Order ID: # {{$order->order_no}}</strong><br><br>
   <strong>Order Date:</strong> {{ date('d-m-Y', strtotime($order->created_at)) }}<br><br>
    <strong>Invoice Date:</strong> {{ date('d-m-Y', strtotime($order->invoice_date)) }}</br><br>

    </p>
  </div>

  <div class="column">
    <p><strong>Shipping Address</strong><br>
    
    {{$order->orderAddresses->name}}<br>
{{$order->orderAddresses->build_name}}<br>
{{$order->orderAddresses->location}}<br>
Phone: {{$order->orderAddresses->mobile}}<br></p>
    
  </div> 
</div>
<hr>
<table>
  
  <tr>
    <td style="text-align:center"><strong>SI</strong></td>
    <td style="text-align:center"><strong>Name</strong></td>    
    <!-- <td style="text-align:center"><strong>Description</strong></td> -->
    <td style="text-align:center"><strong>Unit Price</strong></td>
    <td style="text-align:center"><strong>Qty</strong></td>
    <td style="text-align:center"><strong>Total</td></strong>
     
  </tr>
<!-- <tr><td colspan="10"><hr></td></tr> -->
  @php
        $i = 1;
        $unit_price = 0;
        $product_quantity = 0;
        $total = 0;

        $cost = $order->shipping_cost;

        if($order->prev_shipping_cost != null) {
            $cost = $order->prev_shipping_cost;
        }

        if($order->dicount != null) {
            $discount = $order->dicount;
        }
    @endphp
    @foreach($order->OrderProducts as $product)
    <tr>
    <td style="text-align:center">{{$i}}</td>
    <td style="text-align:center">{{$product->product->name}}</td>
    <td style="text-align:center">Rs. {{number_format($product->product_price, 2)}}</td>
    <td style="text-align:center">{{$product->product_quantity}}</td>
    <td style="text-align:center">Rs. {{number_format($product->tot_price, 2)}}</td>
    </tr>


    @php
    $i += 1;
    $unit_price += $product->product_price;
    $product_quantity += $product->product_quantity;
    $total += $product->tot_price;

    @endphp
    @endforeach
   <!-- <tr><td colspan="10"><hr></td></tr> -->
    <tr>
    
    <td></td>
    <td style="text-align:center"><strong>Total</strong></td>
    <td style="text-align:center"><strong>Rs. {{number_format($unit_price, 2)}}</strong></td>
    <td style="text-align:center"><strong>{{$product_quantity}}</strong></td>
    <td style="text-align:center"><strong>Rs. {{number_format($total, 2)}}</strong></td>
   
  </tr>
  
  @php
  $delivery_fee = $order->delivery_fee == null ? 0 : number_format($order->delivery_fee, 2);
  @endphp
  
  <!-- <tr><td colspan="10"><hr></td></tr> -->
  <table>
    @if($delivery_fee != 0)
    <tr>
    <td class="" style="text-align:right; width: 75%">Delivery Fee</td>
    <td class="" style="text-align:center"><strong>Rs. {{number_format($delivery_fee, 2)}}</strong></td>
    </tr>
    @endif

    @if($order->points != null || $order->points != 0)
    <tr>
    <td class="" style="text-align:right; width: 75%">Points</td>
    <td class="" style="text-align:center"><strong> - {{$order->points}}</strong></td>
    </tr>
    @endif

    <tr>
    
    <td class="ex3" style="text-align:right; width: 75%">Grand Total</td>
    <td class="ex3" style="text-align:center"><strong>Rs. {{number_format($order->amount, 2)}}</strong></td>
    </tr>
  </table>
   
     
  
</table>

<div style="text-align: right;max-width: 300px; margin-left: auto;">
  <p style="text-align: center;margin-top: 30px;">Dafy</p>
<br><br>
<p style="text-align: center;">Authorized Signatory</p>
</div>

 


</body>
</html>

