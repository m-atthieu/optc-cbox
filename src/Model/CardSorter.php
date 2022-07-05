<?php

namespace App\Model;

class CardSorter
{
    public function __construct(
        private TypeOrder $typeSorter,
        private RarityOrder $raritySorter,
        private FamilyOrder $familySorter
    ) {
    }

    private function sort()
    {
        usort($this->cards, function ($a, $b) {
            $typeOrder = new TypeOrder();
            $rarityOrder = new RarityOrder();
            //$familyOrder = new FamilyOrder;
            // first on type
            $ta = $typeOrder->getForCard($a);
            $tb = $typeOrder->getForCard($b);
            if ($ta == $tb) {
                // second on rarity
                $ra = $rarityOrder->getForCard($a);
                $rb = $rarityOrder->getForCard($b);
                if ($ra == $rb) {
                    // third on familyOrder
                    $fa = $this->familyOrder->getForCard($a);
                    $fb = $this->familyOrder->getForCard($b);
                    if ($fa == $fb) {
                        // fourth on unit_id
                        $ia = $a->unit_id;
                        $ib = $b->unit_id;
                        if ($ia == $ib) {
                            // if it's the same unit, they're sorted by level
                            $la = $a->lvl;
                            $lb = $b->lvl;
                            return $la - $lb;
                        } else {
                            return $ia - $ib;
                        }
                    } else {
                        return $fa - $fb;
                    }
                } else {
                    return $ra - $rb;
                }
            } else {
                return $ta - $tb;
            }
        });
    }
}
