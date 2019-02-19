<?php

namespace Okipa\LaravelTable\Test\Fakers;

use Okipa\LaravelTable\Test\Models\Company;
use Okipa\LaravelTable\Test\Models\User;

trait CompaniesFaker
{
    public $data;

    public function createMultipleCompanies(int $count)
    {
        for ($ii = 0; $ii < $count; $ii++) {
            $this->createUniqueCompany();
        }

        return app(Company::class)->all();
    }

    public function createUniqueCompany()
    {
        $databaseUser = app(Company::class)->create($this->generateFakeCompanyData());

        return app(Company::class)->find($databaseUser->id);
    }

    public function generateFakeCompanyData()
    {
        $max = User::all()->count();

        return [
            'name'     => $this->faker->company,
            'owner_id' => rand(1, $max),
            'turnover' => rand(1000, 99999),
        ];
    }
}
