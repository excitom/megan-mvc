<?php
class ScrollView extends View {

	private $msg = '';	// completion message
	
	public function __construct( $title = 'Demonstrate Infinite Scrolling' ) {
		$this->setNavBarActive('examples');
		parent::__construct( $title );
		$this->addScriptLink('/jquery.jscroll.min.js');
	}

	public function setMsg($msg) {
		$this->msg = $msg;
	}

	/**
	 * Generate the middle section of the home page
	 */
	protected function getMainSection() {

		$js =<<<JS
$('.scroll').jscroll({
	loadingHtml: '<span class="text-success">Loading ...</span>'
});
JS;
		$this->addInlineJs($js);

		return <<<HTML
<div class="container theme-showcase" role="main">
  <div class="jumbotron">
	<h1>Demonstrate Infinite Scrolling</h1>
	<p>
	This example uses a jQuery plugin to implement infinite scrolling. 
	As the page visitor scrolls down, more content is constantly loaded.
	I'm using the Lorem Ipsum generator to get random blobs of text.
	</p>
  </div>
</div>
<div class="row">
  <div class="col-md-offset-1 col-md-10">
  <div class="scroll">
    <h3>This Is The Start Of The Scrolling Area</h3>
<p>Atqui perspicuum est hominem e corpore animoque constare, cum primae sint animi partes, secundae corporis. Laelius clamores sofòw ille so lebat Edere compellans gumias ex ordine nostros. Ergo omni animali illud, quod appetiti positum est in eo, quod naturae est accommodatum. Varietates autem iniurasque fortunae facile veteres philosophorum praeceptis instituta vita superabat. Immo vero, inquit, ad beatissime vivendum parum est, ad beate vero satis. Quis Pullum Numitorium Fregellanum, proditorem, quamquam rei publicae nostrae profuit, non odit? Nemo est igitur, quin hanc affectionem animi probet atque laudet, qua non modo utilitas nulla quaeritur, sed contra utilitatem etiam conservatur fides. Video equidem, inquam, sed tamen iam infici debet iis artibus, quas si, dum est tener, conbiberit, ad maiora veniet paratior. Si enim sapiens aliquis miser esse possit, ne ego istam gloriosam memorabilemque virtutem non magno aestimandam putem. </p>

    <a href="/AjaxScroll">more...</a>
  </div>
  </div>
</div>
HTML;
	}
}
