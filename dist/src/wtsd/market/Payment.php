<?php
namespace wtsd\market;

use wtsd\common;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Payment
{
	protected $mrh_login;
	protected $mrh_pass1;
	protected $mrh_pass2;

	protected $inv_id;
	protected $inv_desc = "Товары для животных";
	protected $out_summ = "1";
	protected $shp_item = 1;

	protected $is_test = 1;

	protected $in_curr = "";
	protected $culture = "ru";
	protected $encoding = "utf-8";

	protected $crc;

	public function __construct($outSum = 100, $shpItem = 1, $invId = 678678)
	{

		$paymentConfig = \wtsd\common\Register::get('config', 'payment');

		$this->mrh_login = $paymentConfig['mrh_login'];
		$this->mrh_pass1 = $paymentConfig['mrh_pass1'];
		$this->mrh_pass2 = $paymentConfig['mrh_pass2'];

		$this->out_summ = $outSum;
		$this->shp_item = $shpItem;
		$this->inv_id = $invId;

		$this->crc  = md5("{$this->mrh_login}:{$this->out_summ}:{$this->inv_id}:{$this->mrh_pass1}:Shp_item={$this->shp_item}");

	}

	public function generateScriptUrl()
	{
		return "https://auth.robokassa.ru/Merchant/PaymentForm/FormFLS.js?".
      "MrchLogin={$this->mrh_login}&OutSum={$this->out_summ}&InvId={$this->inv_id}&IncCurrLabel={$this->in_curr}".
      "&Desc={$this->inv_desc}&SignatureValue={$this->crc}&Shp_item={$this->shp_item}".
      "&Culture={$this->culture}&Encoding={$this->encoding}&IsTest={$this->is_test}";
	}

	public function generateForm()
	{
		return '<form action="https://merchant.roboxchange.com/Index.aspx" method="post">
      <input type="hidden" name="MrchLogin" value="{$this->mrh_login}"">
      <input type="hidden" name="OutSum" value="{$this->out_summ}">
      <input type="hidden" name="InvId" value="{$this->inv_id}">
      <input type="hidden" name="Desc" value="{$this->inv_desc}">
      <input type="hidden" name="SignatureValue" value="{$this->crc}">
      <input type="hidden" name="Shp_item" value="{$this->shp_item}">
      <input type="hidden" name="IncCurrLabel" value="{$this->in_curr}">
      <input type="hidden" name="Culture" value="{$this->culture}">
      <input type="submit" value="Pay">
      </form>';
	}

	public function checkResult($out_summ, $inv_id, $shp_item, $crc)
	{
		$crc = strtoupper($crc);
		$my_crc = strtoupper(md5("{$this->out_summ}:{$this->inv_id}:{$this->mrh_pass2}:Shp_item={$this->shp_item}"));

		return ($my_crc != $crc);
	}

}