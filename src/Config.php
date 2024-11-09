<?php

namespace CityChronicles;

class Config {
    public const PATH_CITY = "/city"; 
    public const PATH_GODS = "/gods"; 
    public const PATH_CHARACTERS = "/characters"; 

    public const PATH_EVENTS = '/DataEvents';

    public const FILENAME_EVENTS_NEUTRAL = '/events_neutral.csv';
    public const EVENT_NEUTRAL_ID = 0;

    public const FILENAME_EVENTS_EVIL = '/events_evil.csv';
    public const EVENT_EVIL_ID = -1;

    public const FILENAME_EVENTS_GOODNESS = '/events_goodness.csv';
    public const EVENT_GOODNESS_ID = 1;

    public const FILENAME_DRINK_TIME = '/drink_time.csv';
    public const EVENT_DRINK_TIME_ID = 2;

    public const PATH_LOGS = "/logs";
}