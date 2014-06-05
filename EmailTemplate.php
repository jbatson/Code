<?php
class EmailTemplate
{
	public 	$emailData;
	private $templateId;
	private $dataArray;
	
	public function GenerateEmail($template_id = '', $data_array = array())
	{
		$this->setTemplateId($template_id);
		$this->setDataArray($data_array);
		
		if(isset($this->templateId) && !empty($this->templateId) && $this->templateId > 0)
		{
			$tempQuery = mysql_query("SELECT * FROM email_templates WHERE template_id='".$this->templateId."'");
			$tempResults = mysql_fetch_object($tempQuery);
			
			$templateData = html_entity_decode($tempResults->template_data, ENT_QUOTES);
			
			foreach($this->dataArray as $key => $value)
			{
				$templateData = str_replace('[['.$key.']]', $value, $templateData);
			}
			
			$this->setEmailData($templateData);
			
			return $this->getEmailData();
		}
	}	
	
	private function setEmailData($email_data)
	{
		$this->emailData = $email_data;
	}
	
	private function getEmailData()
	{
		return $this->emailData;	
	}
	
	private function setTemplateId($template_id)
	{
		$this->templateId = $template_id;
	}
	
	private function getTemplateId()
	{
		return $this->templateId;
	}
	
	private function setDataArray($data_array)	
	{
		$this->dataArray = $data_array;
	}
	
	private function getDataArray()	
	{
		return $this->dataArray;
	}	
}
?>