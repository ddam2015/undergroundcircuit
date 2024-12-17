<div class="spp-container"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js?loc=4"></script>
<script src="https://media.sportspassports.com/js/sportspassports.features.min.js"></script>
<link rel="stylesheet" id="spp-css" href="https://media.sportspassports.com/css/spp.style.min.css?version=1" type="text/css" media="all" />
<script>
 $( document ).ready(function(){
  var url = 'https://sportspassports.com/api-data-form/';
  var request_url = document.URL;
  var api_keys = 'SDRzVzV4RzI1ZWxSbTBsaUI3Ujk2Yzc1aE5Hd0g1TVBzeEVBdk84d0liMFJMRFJZRHI1SkZTYU9tdz09';
  var secret_keys = 'cmlyQWRIazRINzFLUldKUFV0ME83dz09';
  var get_url = new URL(url);
  get_url.searchParams.append('api_keys', api_keys);
  get_url.searchParams.append('secret_keys', secret_keys);
  get_url.searchParams.append('request_url', request_url);
  $.ajax({
   type: 'GET',
   url: get_url.href,
   success: function(response){
    $('.spp-container').append(response);
   }
  })
 });
</script>