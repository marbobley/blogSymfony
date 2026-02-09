<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum OgType: string
{
    case WEBSITE = 'website';
    case ARTICLE = 'article';
    case BOOK = 'book';
    case PROFILE = 'profile';
    case VIDEO_MOVIE = 'video.movie';
    case VIDEO_EPISODE = 'video.episode';
    case VIDEO_OTHER = 'video.other';
    case MUSIC_SONG = 'music.song';
    case MUSIC_ALBUM = 'music.album';
    case MUSIC_PLAYLIST = 'music.playlist';
}
