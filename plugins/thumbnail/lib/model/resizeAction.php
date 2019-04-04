<?php
/**
 * @package plugins.thunmbnail
 * @subpackage model
 */

class resizeAction extends imagickAction
{
	protected $newWidth;
	protected $newHeight;
	protected $currentWidth;
	protected $currentHeight;
	protected $bestFit;
	protected $filterType;
	protected $blur;
	protected $shouldUseResize;

	protected function extractActionParameters()
	{
		$this->currentWidth = $this->image->getImageWidth();
		$this->currentHeight = $this->image->getImageHeight();
		$this->newWidth = $this->getIntActionParameter(kThumbnailParameterName::WIDTH);
		$this->newHeight = $this->getIntActionParameter(kThumbnailParameterName::HEIGHT);
		$this->filterType = $this->getActionParameter(kThumbnailParameterName::FILTER_TYPE, Imagick::FILTER_LANCZOS);
		$this->blur = $this->getFloatActionParameter(kThumbnailParameterName::BLUR, 1);
		$this->bestFit = $this->getBoolActionParameter(kThumbnailParameterName::BEST_FIT);
		$this->shouldUseResize = true;
		if($this->newHeight > $this->currentHeight && $this->newWidth > $this->currentWidth)
		{
			$this->shouldUseResize = false;
		}
	}

	function validateInput()
	{
		$this->validateDimensions();
	}

	protected function validateDimensions()
	{
		if($this->bestFit && $this->newWidth < 1)
		{
			KExternalErrors::dieError(KExternalErrors::BAD_QUERY, 'If bestfit parameter width must be positive');
		}

		if($this->bestFit && $this->newHeight < 1)
		{
			KExternalErrors::dieError(KExternalErrors::BAD_QUERY, ' If bestfit parameter height must be positive');
		}

		if(!is_numeric($this->newWidth) || $this->newWidth < 0 || $this->newWidth > 10000)
		{
			KExternalErrors::dieError(KExternalErrors::BAD_QUERY, 'width must be between 0 and 10000');
		}

		if(!is_numeric($this->newHeight) || $this->newHeight < 0 || $this->newHeight > 10000)
		{
			KExternalErrors::dieError(KExternalErrors::BAD_QUERY, 'height must be between 0 and 10000');
		}


	}

	protected function doAction()
	{
		if($this->shouldUseResize)
		{
			$this->image->resizeImage($this->newWidth, $this->newHeight, $this->filterType, $this->blur, $this->bestFit);
		}
		else
		{
			$this->image->scaleImage($this->newWidth, $this->newHeight, $this->bestFit);
		}

		return $this->image;
	}
}