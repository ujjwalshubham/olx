<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'FAQ';
$this->params['breadcrumbs'][] = $this->title;
$baseurl =\Yii::$app->getUrlManager()->getBaseUrl(); 
?>


<section id="main" class="category-page mid_container">
		<div class="container">
			<!-- breadcrumb -->
			<div class="breadcrumb-section">
                <ol class="breadcrumb">
                        <li><a href="<?php echo $baseurl; ?>"><i class="fa fa-home"></i> <?php echo Yii::t('frontend', 'Home')?></a></li>
						<li><?php echo $title?> </li>
                        <div class="ml-auto back-result"><a href=""><i class="fa fa-angle-double-left"></i>
                            <?php echo Yii::t('frontend', 'Back to Results')?></a>
                </div>
            </ol>
				
				<h2 class="title"><?php echo $title?></h2>
                
        </div>
			<!-- breadcrumb -->
			
			
			<div class="row recommended-ads">
				
				<div class="col-sm-12">
					<div class="section faq_page ">
					
						
            <?php if(isset($faqs) && !empty($faqs)){
			foreach($faqs as $key=>$faq){	
			?>
            <dl class="faq-list">
                <dt class="faq-list_h">
                    <h4 class="marker">Q?</h4>
                    <h5 class="marker_head"><?= $faq['title']?></h5>
                </dt>
                <dd>
                    <h4 class="marker1">A.</h4>
                    <div class="m_13"> <p><?= $faq['description']?></p></div>
                </dd>
            </dl>
            <?php }} ?>
            
            
						
						
				</div>
				</div>
				
			</div>
			
		</div>
	</section>
	

