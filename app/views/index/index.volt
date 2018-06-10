{% cache cache_key cache_life_time %}
<div class="page-header">
    <h1>Congratulations!</h1>
</div>

<p>You're now flying with Phalcon. Great things are about to happen!</p>

<p>This page is located at <code>views/index/index.volt</code></p>

Translation:
<p>hi -> {{ hi }}</p>
<p>hi-name -> {{ hi_with_name }}</p>
<p>bye -> {{ bye }}</p>

{% endcache %}
