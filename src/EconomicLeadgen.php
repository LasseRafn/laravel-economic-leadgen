<?php namespace LasseRafn\EconomicLeadgen;

use GuzzleHttp\Client;
use LasseRafn\CvrApi\CvrApi;
use LasseRafn\EconomicLeadgen\Errors\CurlError;

class EconomicLeadgen
{
	protected $endpoint = 'https://secure.e-conomic.com';
	private   $partnerKey;
	private   $partnerAgreementNo;
	private   $client;
	private   $cvrapi;

	function __construct()
	{
		$this->cvrapi             = new CvrApi();
		$this->partnerKey         = config( 'economic-leadgen.partner_key' );
		$this->partnerAgreementNo = config( 'economic-leadgen.partner_agreement_no' );

		$this->client = new Client( [
			'base_uri' => $this->endpoint,
			'headers'  => [
				'Content-Type' => 'application/x-www-form-urlencoded'
			]
		] );
	}

	/**
	 * @param $userName
	 * @param $userEmail
	 * @param $companyName
	 *
	 * @return mixed
	 * @throws CurlError
	 */
	public function signup( $userName, $userEmail, $companyName )
	{
		try
		{
			$business = $this->cvrapi->get( $companyName );

			$companyName = $business->name;
		} catch ( \Exception $ex )
		{
			\Log::error( $ex->getMessage() );
		}

		try
		{
			$response = $this->client->post( '/secure/signup/partnertrial/', [
				'form_params'  => [
					'UserEmail'          => $userEmail,
					'UserName'           => $userName,
					'CompanyName'        => $companyName,
					'PartnerAgreementNo' => $this->partnerAgreementNo,
					'PartnerKey'         => $this->partnerKey
				],
				'Content-Type' => 'application/x-www-form-urlencoded'
			] );

			return str_replace( 'OK: ', '', $response->getBody()->getContents() );
		} catch ( \Exception $exception )
		{
			throw new CurlError( $exception->getMessage(), $exception->getCode(), $exception->getPrevious() );
		}

	}
}