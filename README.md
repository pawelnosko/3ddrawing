<h1>3Ddrawing &mdash; Lightweight PHP Library for Technical 3D Drawings</h1>
<p><strong>3Ddrawing</strong> is a lightweight PHP library for generating isometric technical drawings of simple 3D structures and buildings in SVG format. It was created to solve a practical problem: quickly generating clean technical sketches for garages, sheds, houses, and similar constructions without using heavy CAD software or browser-based WebGL engines.</p>
<p>The library works entirely on the server side and allows developers to generate drawings dynamically from form data or database values such as width, depth, height, roof slope, doors, and windows.</p>
<hr/>
<h2>✨ Features</h2>
<ul>
<li>Generate isometric SVG technical drawings</li>
<li>Draw 3D boxes and building structures</li>
<li>Support for mono-pitch and asymmetric roofs</li>
<li>Add doors, windows, and gates</li>
<li>Automatic dimension arrows and labels</li>
<li>Export as SVG or standalone HTML</li>
<li>Easy PDF integration</li>
<li>No client-side JavaScript required</li>
<li>Lightweight architecture with pure PHP</li>
</ul>
<p>Perfect for:</p>
<ul>
<li>product configurators,</li>
<li>quotation systems,</li>
<li>technical documentation,</li>
<li>PDF offer generators,</li>
<li>garage and building visualization tools.</li>
</ul>
<hr/>
<h2>🛠 Technology Stack</h2>
<p>3Ddrawing is built with modern PHP 8.1+ and uses:</p>
<ul>
<li>PSR-4 Composer autoloading,</li>
<li>SVG as the output format,</li>
<li>custom 3D &rarr; 2D isometric projection math,</li>
<li>pure PHP without external graphics libraries.</li>
</ul>
<p>Because of its simplicity, the library can run on almost any standard PHP hosting environment.</p>
<hr/>
<h2>📦 Main Components</h2>
<p>The library provides a simple API for drawing:</p>
<pre><code>$drawing-&gt;line3D();<br/>$drawing-&gt;box3D();<br/>$drawing-&gt;face3D();<br/>$drawing-&gt;text3D();<br/>$drawing-&gt;arrowDimension();</code></pre>
<p>Included building helpers:</p>
<ul>
<li><code>GarageDrawer</code></li>
<li><code>HouseDrawer</code></li>
<li><code>BarnDrawer</code></li>
<li><code>WorkshopDrawer</code></li>
<li><code>GreenhouseDrawer</code></li>
</ul>
<hr/>
<h2>📐 Automatic Dimensions</h2>
<p>Generate technical dimensions with arrows and labels:</p>
<pre><code>$drawing-&gt;arrowDimension(<br/> [0, 0, 0],<br/> [600, 0, 0],<br/> 'Width: 600 cm'<br/>);</code></pre>
<hr/>
<h2>🏠 Example &mdash; Garage Drawing</h2>
<pre><code>(new GarageDrawer($drawing))-&gt;draw(<br/> size: ['width' =&gt; 600, 'depth' =&gt; 400, 'height' =&gt; 250],<br/> roof: ['rise' =&gt; 70],<br/> door: ['x' =&gt; 180, 'z' =&gt; 0, 'width' =&gt; 240, 'height' =&gt; 220],<br/>);</code></pre>
<hr/>
<h2>📄 PDF Integration</h2>
<p>The library itself generates SVG or HTML output, which can be embedded into:</p>
<ul>
<li>Dompdf,</li>
<li>wkhtmltopdf,</li>
<li>mPDF,</li>
<li>Inkscape pipelines,</li>
<li>custom document generators.</li>
</ul>
<p>This makes it ideal for automatically generating professional offers and technical attachments.</p>
<hr/>
<h2>⚠ Limitations</h2>
<p>3Ddrawing intentionally stays lightweight and simple, so it has some limitations:</p>
<ul>
<li>isometric projection only,</li>
<li>no hidden-edge removal,</li>
<li>not a full CAD replacement,</li>
<li>no photorealistic rendering,</li>
<li>no interactive 3D camera rotation.</li>
</ul>
<p>However, it is highly effective for fast technical visualization workflows and business-oriented document generation.</p>
<hr/>
<h2>📄 License</h2>
<p>MIT License</p>
<hr/>
<h2>👨&zwj;💻 Author</h2>
<p>Paweł Nosko</p>
