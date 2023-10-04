<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Document</title> -->
</head>
<body style="margin:0;font-family: 'Poppins', sans-serif;color: #404553;">
    <div class="mail" style="background-color: #efefef; padding: 30px;">
        <div class="mailBox" style="max-width: 700px; margin-left: auto; margin-right: auto; background-color: #fff; padding: 30px; border-radius: 5px;">
            <!-- <div class="logo" style="display: flex; padding-bottom: 10px;     border-bottom: 1px solid #efefef;">
                <img src="http://localhost/dafy/public/admin/images/dafy_logo.png" class="img-fluid" alt="logo" style="margin-left: auto; margin-right: auto;">
            </div> -->
            <div class="mailContent">
                <h2 style="font-family: sans-serif; color: #727272; font-size: 17px;    padding-top: 50px;">Hi {{$name}},</h2>
                <p style="font-family: sans-serif; color: #727272; font-size: 14px;">A copy of the invoice for your purchasing order #{{$order_id}} has been attached for your reference.</p>
                <br>
                <br>
                <p style="font-family: sans-serif; color: #727272; font-size: 14px;">Regards,</p>
                <p style="font-family: sans-serif; color: #727272; font-size: 14px;">Dafy Team</p>
               
            </div>
        </div>
    </div>
</body>
</html>