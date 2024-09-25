<?php

namespace Riddle\Api\Builder\Objects;

/**
 * This sets up the data object for a single Result.
 * @see https://www.riddle.com/help/api/build-riddles/result-pages
 */
class ResultPage
{
    private array $blocks = [];

    public function addTextBlock(string $text, int $marginTop = 0, int $marginBottom = 0): self
    {
        $this->blocks[] = [
            'type' => 'Text',
            'text' => $text,
            'marginTop' => $marginTop,
            'marginBottom' => $marginBottom,
        ];

        return $this;
    }

    public function addButton(string $label, string $url, bool $openInNewTab = false, int $marginTop = 0, int $marginBottom = 0): self
    {
        $this->blocks[] = [
            'type' => 'Button',
            'label' => $label,
            'url' => $url,
            'isOpenInNewTabEnabled' => $openInNewTab,
            'marginTop' => $marginTop,
            'marginBottom' => $marginBottom,
        ];

        return $this;
    }

    public function addSocialShare(string $label, string $shareMessage = '', string $shareDescription = '', ?string $shareRedirectUrl = null, array $networks = ['facebook', 'whatsapp', 'twitter'], int $marginTop = 0, int $marginBottom = 0): self
    {
        $shareBlock = [
            'type' => 'Share',
            'label' => $label,
            'networks' => $networks,
            'marginTop' => $marginTop,
            'marginBottom' => $marginBottom,
        ];

        if ('' !== $shareMessage) {
            $shareBlock['messageTitle'] = $shareMessage;
        }

        if ('' !== $shareDescription) {
            $shareBlock['description'] = $shareDescription;
        }

        if (null !== $shareRedirectUrl) {
            $shareBlock['redirectUrl'] = $shareRedirectUrl;
        }

        $this->blocks[] = $shareBlock;

        return $this;
    }

    public function addAnsweredBlocks(bool $areTotalVotesVisible = true, bool $isPercentageVisible = true, bool $isVotesNumberVisible = true, bool $areAnswerImagesHidden = false, bool $areMainImagesHidden = false, int $marginTop = 0, int $marginBottom = 0): self
    {
        $this->blocks[] = [
            'type' => 'AnsweredBlocks',
            'areTotalVotesVisible' => $areTotalVotesVisible,
            'isPercentageVisible' => $isPercentageVisible,
            'isVotesNumberVisible' => $isVotesNumberVisible,
            'areAnswerImagesHidden' => $areAnswerImagesHidden,
            'areMainImagesHidden' => $areMainImagesHidden,
            'marginTop' => $marginTop,
            'marginBottom' => $marginBottom,
        ];

        return $this;
    }

    public function addMedia(string $mediaUrl, int $marginTop = 0, int $marginBottom = 0): self
    {
        $this->blocks[] = [
            'type' => 'Media',
            'media' => $mediaUrl,
            'marginTop' => $marginTop,
            'marginBottom' => $marginBottom,
        ];

        return $this;
    }

    public function addLeaderboard(string $leaderboardUUID, int $marginTop = 0, int $marginBottom = 0): self
    {
        $this->blocks[] = [
            'type' => 'Leaderboard',
            'leaderboard' => $leaderboardUUID,
            'marginTop' => $marginTop,
            'marginBottom' => $marginBottom,
        ];

        return $this;
    }

    public function getBlocks(): array
    {
        return $this->blocks;
    }
}