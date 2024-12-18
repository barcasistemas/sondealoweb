<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="robots" content="noindex">
    <title>Menú</title>
    <style type="text/css">
      *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: sans-serif;
      }
      header{
        width: 100%;
        height: 50px;
        border: 1px solid rgba(0,0,0,0.2);
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
      }
      iframe{
        min-width: 100vw;
        min-height: 100vh;
      }
      .container{
        margin: auto;
        width: 95%;
        max-width: 1024px;
      }
      header a{
        display: inline-flex;
        padding: 1rem;
        color: #0000ff;
        text-decoration: none;
      }
      .selected{
        background-color: rgba(0,0,0,0.9);
        color: #ffffff;
        }
    </style>
  </head>
  <body>
    <header>
      <div class="container">
        @if($url_alimentos != '')
          <a style="float:left;" id="btn_alimentos" onclick="setUrl('{{$url_alimentos}}', 'btn_alimentos')" >{{$label_alimentos}}</a>
        @endif
        @if ($url_bebidas != '')
          <a style="float:right;" id="btn_bebidas" onclick="setUrl('{{$url_bebidas}}', 'btn_bebidas')" >{{$label_bebidas}}</a>
        @endif
      </div>
    </header>
    <iframe id="embed-menu" src="" type="application/pdf"></iframe>
    <script type="text/javascript">
      let embed = document.getElementById('embed-menu');

      function setUrl(url, elem){
        embed.setAttribute('src',url);
        let array = document.getElementsByTagName('a');
        for (var i = 0; i < array.length; i++) {
          array[i].classList.remove('selected');
        }
        document.getElementById(elem).classList.add('selected');
      }
      document.addEventListener('DOMContentLoaded', function(){
        setUrl('{{$url_alimentos}}','btn_alimentos');
      });
    </script>
  </body>
</html>
