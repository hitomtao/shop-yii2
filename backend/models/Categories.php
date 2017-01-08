<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property integer $confirmed
 *
 * @property Categories $parent
 * @property Categories[] $categories
 * @property Products[] $products
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'confirmed'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ردیف'),
            'parent_id' => Yii::t('app', 'والد'),
            'name' => Yii::t('app', 'نام'),
            'confirmed' => Yii::t('app', 'فعال'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Categories::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Categories::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::className(), ['category_id' => 'id']);
    }
    
    public function getCategoriesOptionsCreate(){
        $item[''] = 'بدون والد';
        foreach (Categories::find()->where(['parent_id' => NULL])->all() as $category){
            $item[$category->id] = $category->name;
        }
        return $item;
    }
    public function getCategoriesOptionsEdit(){
        $item[''] = 'بدون والد';
        foreach (Categories::find()->where('parent_id IS NULL AND id<>:id', [':id' => $this->id])->all() as $category){
            $item[$category->id] = $category->name;
        }
        return $item;
    }
}
