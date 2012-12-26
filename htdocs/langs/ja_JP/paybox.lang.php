<?php
/* Copyright (C) 2012	Regis Houssin	<regis@dolibarr.fr>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

$paybox = array(
		'CHARSET' => 'UTF-8',
		'PayBoxSetup' => '切符売り場のモジュールのセットアップ',
		'PayBoxDesc' => 'このモジュールは、上の支払いを可能にするためにページを提供して<a href="http://www.paybox.com" target="_blank">切符売り場</a>の顧客によって。これはフリーの支払いのためにまたは特定のDolibarrオブジェクトの支払いに用いることができる（請求書、発注、...）',
		'FollowingUrlAreAvailableToMakePayments' => '以下のURLはDolibarrオブジェクト上で支払いをするために顧客にページを提供するために利用可能です',
		'PaymentForm' => '支払い形態',
		'WelcomeOnPaymentPage' => 'ようこそ私たちのオンライン決済サービスについて',
		'ToOfferALinkForOnlinePaymentOnOrder' => '顧客の注文のための%sオンライン決済のユーザインタフェースを提供するためのURL',
		'ToOfferALinkForOnlinePaymentOnInvoice' => '顧客の請求書の%sオンライン決済のユーザインタフェースを提供するためのURL',
		'ToOfferALinkForOnlinePaymentOnContractLine' => '契約回線の%sオンライン決済のユーザインタフェースを提供するためのURL',
		'ToOfferALinkForOnlinePaymentOnFreeAmount' => '空き容量のため夜中オンライン決済のユーザインタフェースを提供するためのURL',
		'ToOfferALinkForOnlinePaymentOnMemberSubscription' => 'メンバーのサブスクリプションの%sオンライン決済のユーザインタフェースを提供するためのURL',
		'MessageOK' => '検証済みペイメントの戻りページでメッセージ',
		'MessageKO' => 'キャンセル支払い戻りページでメッセージ',
		'ThisScreenAllowsYouToPay' => 'この画面では、%sにオンライン決済を行うことができます。',
		'ThisIsInformationOnPayment' => 'これは、実行する支払いに関する情報です。',
		'ToComplete' => '完了する',
		'YourEMail' => '入金確認を受信する電子メール',
		'Creditor' => '債権者',
		'PaymentCode' => '支払いコード',
		'PayBoxDoPayment' => '支払いに行く',
		'YouWillBeRedirectedOnPayBox' => 'あなたが入力するクレジットカード情報をセキュリティで保護された切符売り場のページにリダイレクトされます。',
		'PleaseBePatient' => '、もうしばらくお待ちください',
		'Continue' => '次の',
		'ToOfferALinkForOnlinePayment' => '%s支払いのURL',
		'YouCanAddTagOnUrl' => 'また、独自の支払いコメントタグを追加するには、それらのURL（無料支払のためにのみ必要）のいずれかにurlパラメータ<b>·タグ= <i>値を</i></b>追加<i><b>する</b></i>こと<i><b>が</b></i>できます。',
		'SetupPayBoxToHavePaymentCreatedAutomatically' => '切符売り場で検証したときに自動的に作成支払いを持っているのurl <b>%s</b>を使用して切符売り場をセットアップします。',
		'YourPaymentHasBeenRecorded' => 'このページでは、あなたの支払が記録されていることを確認します。ありがとうございます。',
		'YourPaymentHasNotBeenRecorded' => 'あなたの支払は記録されていないトランザクションがキャンセルされました。ありがとうございます。',
		'AccountParameter' => 'アカウントのパラメータ',
		'UsageParameter' => '使用パラメータ',
		'InformationToFindParameters' => 'あなたの%sアカウント情報を見つけるのを助ける',
		'PAYBOX_CGI_URL_V2' => '支払いのために切符売り場CGIモジュールのurl',
		'VendorName' => 'ベンダーの名前',
		'CSSUrlForPaymentForm' => '支払いフォームのCSSスタイルシートのURL',
);
?>