# I2G
I2G is for converting images to gradients It does this by most of the colors used in the photo. So, when you want to use lazy loading, it can show a good preview of the image being loaded.


## How to use
It's easy. Include the I2G.php file in your script.

<pre><code>  require('I2G.php');
</code></pre>

Then create an instance of the class

<pre><code>  $i2g = new I2G(' /* Your image path */ ');
</code></pre>

And then you can get the gradient code by calling the get_aa method

<pre><code>echo '&lt;div style="margin-left:20px;
                  width:200px;
                  height:200px;
                  background:'. $i2g->get_gradients() .';">&lt;/div>';
</code></pre>
