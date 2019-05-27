<head>
	<script src="aframe.js" type="text/javascript"  ></script>
</head>

<a-scene background="color: #4267b2">
  <a-assets>
    <img id="my-image" src="https://screengrap-bdube83.c9users.io/responsiveHTML5/<?php echo $_GET['imgURL'];?>">
    <!--<img id="my-image" src="https://screengrap-bdube83.c9users.io/responsiveHTML5/testMihl.png">-->
  </a-assets>

  <!-- Using the asset management system. -->
  <!--<a-sky radius="10"  position="0 0 -6" rotation="0 0 0" width="20%" height="20%" src="#my-image"></a-sky>-->
  
  <a-curvedimage position="10 10 0" height="150%" width="500%" src="#my-image"></a-curvedimage>

</a-scene>