<!DOCTYPE html>
<html lang="en">
<head>
  <title>Form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>API form</h2>
  <form name="api_form" id="api_form">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div class="form-group">
      <label for="pincode">pincode:</label>
      <input type="number" class="form-control" id="pincode" placeholder="Enter pincode" name="pincode" min="5">
    </div>
    <div class="form-group">
      <label for="age">age:</label>
      <input type="number" class="form-control" id="age" placeholder="Enter age" name="age" >
    </div>
    <div class="form-group">
    <label for="pwd">Sum Insured:</label>
    <select class="form-select" aria-label="Default select example" name="sum_insured" id="sum_insured[]">
        <option selected>Open this select menu</option>
        <option id="3" value="300000">300000</option>
        <option id="5" value="500000">500000</option>
        <option id="7" value="700000">700000</option>
        <option id="10" value="1000000">1000000</option>
      </select>
    </div>
   
    <button type="submit" class="btn btn-default">Submit</button>
  </form>
</div>
<div class="container"> 
    Response Data  
    <br>

<div id="response"></div>
</div>


<script>
   $(document).ready(function(){
    $("#api_form").submit(function(e) {

    e.preventDefault();

    var form = $(this);
    var url = "{{ route('api.get_form') }}" ;

    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(data)
        {
            var tableHTML = '<table border="1">';
            tableHTML += '<tr><th>Policy</th><th>Amount</th></tr>';
            tableHTML += '<tr><td>' + data.name + '</td><td>' + data.amount + '</td></tr>';
            tableHTML += '</table>';
            $('#response').html(tableHTML);
        },
        error: function(xhr, status, error) {
            alert("Error: " + xhr.responseText);
        }
    });

    });
 });
   
</script>



</body>
</html>
