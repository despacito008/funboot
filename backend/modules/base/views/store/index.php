<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\components\enums\YesNo;
use common\models\Store as ActiveModel;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel common\models\ModelSearch */

$this->title = Yii::t('app', 'Stores');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><?= !is_null($this->title) ? Html::encode($this->title) : Inflector::camelize($this->context->id);?></h2>
                <div class="card-tools">
                    <?= Html::createModal() ?>
                    <?= Html::buttonModal(['config'], Yii::t('app', 'Refresh Config File'), ['class' => 'btn btn-sm btn-warning'], false) ?>
                    <?= Html::export() ?>
                    <?= Html::import() ?>
                </div>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false,
                        ],

                        'id',
                        'host_name',
                        ['attribute' => 'user_id', 'value' => function ($model) { return $model->user->username; }, 'filter' => true],
                        'name',
                        // ['attribute' => 'name', 'format' => 'raw', 'value' => function ($model) { return Html::field('name', $model->name); }, 'filter' => true,],
                        'description',
                        'expired_at:datetime',
                        // 'remark:ntext',
                        ['attribute' => 'language', 'format' => 'raw', 'value' => function ($model) { return ActiveModel::getLanguageLabels($model->language); }, 'filter' => false,],
                        ['attribute' => 'type', 'format' => 'raw', 'value' => function ($model) { return ActiveModel::getTypeLabels($model->type); }, 'filter' => false,],
                        // ['attribute' => 'sort', 'format' => 'raw', 'value' => function ($model) { return Html::sort($model->sort); }, 'filter' => false,],
                        ['attribute' => 'status', 'format' => 'raw', 'value' => function ($model) { return Html::status($model->status); }, 'filter' => Html::activeDropDownList($searchModel, 'status', ActiveModel::getStatusLabels(null, true), ['class' => 'form-control', 'prompt' => Yii::t('app', 'Please Filter')]),],
                        'created_at:datetime',
                        // 'updated_at:datetime',
                        // 'created_by',
                        // 'updated_by',

                        [
                            'header' => Yii::t('app', 'Actions'),
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{login} {edit} {delete}',
                            'buttons' => [
                                'login' => function ($url, $model, $key) {
                                    return Html::buttonModal(['login', 'id' => $model->id], Yii::t('app', 'Login'), ['class' => 'btn btn-sm btn-success'], false, true);
                                },
                                'edit' => function ($url, $model, $key) {
                                    return Html::editModal(['edit-ajax', 'id' => $model->id]);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['delete', 'id' => $model->id]);
                                },
                            ],
                            'headerOptions' => ['class' => 'action-column action-column-lg'],
                        ],
                    ]
                ]); ?>
            </div>
        </div>
    </div>
</div>
