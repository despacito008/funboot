<?php

namespace backend\modules\tool\controllers;

use Yii;
use common\models\tool\Crud;
use common\models\ModelSearch;
use backend\controllers\BaseController;

/**
 * Crud
 *
 * Class CrudController
 * @package backend\modules\tool\controllers
 */
class CrudController extends BaseController
{
    /**
      * @var Crud
      */
    public $modelClass = Crud::class;

    /**
      * 模糊查询字段
      * @var string[]
      */
    public $likeAttributes = ['name'];

    /**
     * 可编辑字段
     *
     * @var int
     */
    protected $editAjaxFields = ['name', 'sort'];

    /**
     * 导入导出字段
     *
     * @var int
     */
    protected $exportFields = [
        'id' => 'text',
        'name' => 'text',
        'type' => 'select',
    ];


    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $post = Yii::$app->request->post();
                $model->started_at = strtotime($post['Crud']['startedTime']);
                $model->ended_at = strtotime($post['Crud']['endedTime']);
                $model->tag = json_encode($post['Crud']['tags']);
                $model->config = json_encode($post['Crud']['configs']);
                $model->images = json_encode($post['Crud']['imagess']);
                $model->files = json_encode($post['Crud']['filess']);

                if ($model->save()) {
                    $this->flashSuccess();
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->logSystem->db($model->errors);
                    $this->flashError(Yii::t('app', 'Operation Failed') . json_encode($model->errors));
                }
            }
        }

        $model->tags = json_decode($model->tag, true);
        $model->configs = json_decode($model->config, true);
        $model->imagess = json_decode($model->images, true);
        $model->filess = json_decode($model->files, true);
        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }

}
