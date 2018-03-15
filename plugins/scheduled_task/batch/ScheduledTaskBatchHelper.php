<?php

class ScheduledTaskBatchHelper
{
	/**
	 * @param KalturaClient $client
	 * @param KalturaScheduledTaskProfile $scheduledTaskProfile
	 * @param KalturaFilterPager $pager
	 * @param KalturaFilter $filter
	 * @return KalturaObjectListResponse
	 */
	public static function query(KalturaClient $client, KalturaScheduledTaskProfile $scheduledTaskProfile, KalturaFilterPager $pager, $filter = null)
	{
		$objectFilterEngineType = $scheduledTaskProfile->objectFilterEngineType;
		$objectFilterEngine = KObjectFilterEngineFactory::getInstanceByType($objectFilterEngineType, $client);
		$objectFilterEngine->setPageSize($pager->pageSize);
		$objectFilterEngine->setPageIndex($pager->pageIndex);
		if(!$filter)
			$filter = $scheduledTaskProfile->objectFilter;

		return $objectFilterEngine->query($filter);
	}


	/**
	 * @param $entries
	 * @param $createAtTime
	 * @return string
	 */
	public static function getEntriesIdWithSameCreateAtTime($entries, $createAtTime)
	{
		$result = array();
		foreach ($entries as $entry)
		{
			if($entry->createdAt == $createAtTime)
				$result[] = $entry->id;
		}

		return implode (", ", $result);
	}

	public static function getDryRunObjectSeprator()
	{
		return chr(07) . chr(03);
	}
}