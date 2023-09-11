<?php

namespace Model\Database;

class Professions extends Model
{
    const WEAPONSMITHING = 'Weaponsmithing';
    const ARMORING = 'Armoring';
    const JEWELCRAFTING = 'Jewelcrafting';
    const ENGINEERING = 'Engineering';
    const OUTFITTING = 'Outfitting';
    const ALCHEMY = 'Alchemy';
    const COOKING = 'Cooking';

    /**
     * @Column(type='integer', nullable=false)
     * @Primary
     * @Identity
     */
    public int $id;
    /**
     * @Column(nullable=false)
     * @type string
     */
    public string $name;

    public function getList()
    {
        $ret = (object)[];
        foreach (self::find() as $rec) {

        }
    }
}
