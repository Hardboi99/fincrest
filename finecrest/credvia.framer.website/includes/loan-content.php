<?php
/**
 * FINCREST 360 — content for every loan page.
 * Edit text here; the layout lives in includes/loan-page.php.
 * Keep statements general: interest rates, amounts and approval decisions belong to the lending institution.
 */
$ELIG_NOTE = 'Indicative only. Final eligibility is decided by the lending institution.';

return [

    'home-loan' => [
        'h1'    => 'Turn your dream home into <em>reality.</em>',
        'lead'  => 'Explore home loan options with guidance at every step, whether you are buying, building or refinancing.',
        'img'   => 'home.jpg',
        'intro' => [
            'A home loan helps you finance the purchase or construction of a home and repay it in monthly instalments over a chosen tenure.',
            'Fincrest 360 helps you understand your options, estimate your EMI and continue with the relevant lending institution.',
        ],
        'highlights' => ['Buy a new home', 'Build your home', 'Refinance', 'Top-up options'],
        'features' => [
            ['Multiple options', 'Explore home financing options suited to your needs.'],
            ['Flexible tenure planning', 'Estimate repayments for different tenures before you decide.'],
            ['Transparent communication', 'Clear information at every step of your enquiry.'],
            ['Guided support', 'Help from enquiry to the lending institution.'],
        ],
        'eligibility' => ['Minimum age as per the lender', 'Stable source of income', 'Acceptable credit history as per the lender', 'Property that meets the lender\'s criteria'],
        'documents'   => ['Identity proof: PAN, Aadhaar, Passport or Driving Licence', 'Address proof', 'Income proof: salary slips, ITR or bank statements', 'Property documents: sale agreement, title deed and related records'],
        'faqs' => [
            ['What is a home loan?', 'A home loan is financing that lets you buy, build or renovate a home and repay the amount in monthly instalments.'],
            ['Can I use a home loan to build a house?', 'Many lenders offer finance for construction on land you own. Eligibility and conditions are set by the lending institution.'],
            ['Who decides the interest rate and tenure?', 'The lending institution decides these based on its own policies and your profile.'],
            ['How do I get started?', 'Use the enquiry form on this page. Our team will get in touch with the next steps.'],
        ],
        'calc' => ['amount' => 3000000, 'min' => 500000, 'max' => 50000000, 'step' => 100000, 'years' => 20, 'maxYears' => 30],
    ],

    'loan-against-property' => [
        'h1'    => 'Unlock the value <em>of your property.</em>',
        'lead'  => 'Use an eligible property you own to explore secured financing, with clear information and a clear path forward.',
        'img'   => 'property.jpg',
        'intro' => [
            'A loan against property (LAP) is a secured loan where an eligible residential or commercial property is offered as security to the lender.',
            'Fincrest 360 helps you understand how it works, plan your repayments and continue with the relevant lending institution.',
        ],
        'highlights' => ['Property-backed', 'Flexible end-use', 'Repayment planning', 'Guided process'],
        'features' => [
            ['Asset-backed financing', 'Explore options supported by eligible collateral.'],
            ['Plan your repayment', 'Estimate your EMI before you proceed.'],
            ['Clear information', 'Understand valuation, charges and total cost with the lender.'],
            ['Guided support', 'Help from enquiry to the lending institution.'],
        ],
        'eligibility' => ['Ownership of an eligible property with clear title', 'Stable repayment capacity', 'Acceptable credit history as per the lender', 'Property that meets the lender\'s valuation criteria'],
        'documents'   => ['Identity proof: PAN, Aadhaar or equivalent', 'Address proof', 'Income documents: salary slips, ITR or bank statements', 'Property title deed and ownership records', 'Documents requested by the lender for valuation'],
        'faqs' => [
            ['What is a loan against property?', 'It is a loan taken by pledging an eligible property as security. The property stays with you while the loan is being repaid, subject to the lender\'s terms.'],
            ['Which properties may be considered?', 'Lenders typically consider eligible residential or commercial properties. The exact criteria are set by the lending institution.'],
            ['Who values the property?', 'The lending institution carries out valuation and legal checks as part of its process.'],
            ['How do I get started?', 'Use the enquiry form on this page and share your requirement. Our team will get in touch.'],
        ],
        'calc' => ['amount' => 2500000, 'min' => 500000, 'max' => 50000000, 'step' => 100000, 'years' => 10, 'maxYears' => 20],
    ],

    'car-loan' => [
        'h1'    => 'Put your next car <em>within easy reach.</em>',
        'lead'  => 'Explore vehicle financing, estimate your monthly payment and take a clear path to your next car.',
        'img'   => 'car.jpg',
        'intro' => [
            'A car loan helps you spread the cost of a vehicle over regular monthly instalments instead of paying the full price upfront.',
            'Fincrest 360 helps you plan your budget and continue with the relevant lending institution.',
        ],
        'highlights' => ['New car', 'Used car', 'Refinance options', 'EMI planning'],
        'features' => [
            ['Vehicle financing', 'Explore options for your next car purchase.'],
            ['Budget planning', 'Estimate monthly payments for different tenures.'],
            ['Clear guidance', 'Support through vehicle and lender requirements.'],
            ['Transparent communication', 'Straightforward information about the process.'],
        ],
        'eligibility' => ['Minimum age as per the lender', 'Regular source of income (salaried or self-employed)', 'Acceptable credit history as per the lender', 'Vehicle that meets the lender\'s criteria'],
        'documents'   => ['Identity proof: PAN, Aadhaar or equivalent', 'Address proof', 'Income proof or recent bank statements', 'Dealer quotation or vehicle details'],
        'faqs' => [
            ['What is a car loan?', 'A car loan is financing that lets you buy a vehicle and repay the cost in monthly instalments over an agreed tenure.'],
            ['Can I finance a used car?', 'Some lenders finance eligible used vehicles. Conditions are set by the lending institution.'],
            ['Who decides the interest rate and tenure?', 'The lending institution decides these based on its own policies and your profile.'],
            ['How do I get started?', 'Use the enquiry form on this page. Our team will get in touch with the next steps.'],
        ],
        'calc' => ['amount' => 800000, 'min' => 100000, 'max' => 5000000, 'step' => 50000, 'years' => 5, 'maxYears' => 7],
    ],

    'bike-loan' => [
        'h1'    => 'Ride home your <em>dream bike.</em>',
        'lead'  => 'Simple financing support for your two-wheeler purchase. Share your requirement and explore suitable options.',
        'img'   => 'bike.jpg',
        'intro' => [
            'A bike loan helps you spread the cost of a two-wheeler over regular monthly instalments instead of paying the full price upfront.',
            'Fincrest 360 helps you understand your options and move ahead with the relevant lending institution.',
        ],
        'highlights' => ['Two-wheeler financing', 'Guided enquiry', 'EMI planning', 'Clear communication'],
        'features' => [
            ['Two-wheeler financing', 'Options to finance a new two-wheeler purchase.'],
            ['Guided enquiry', 'Clear support from your first enquiry to the next step.'],
            ['Transparent communication', 'Straightforward information about the process.'],
            ['EMI planning', 'Estimate your monthly instalment before you apply.'],
        ],
        'eligibility' => ['Indian resident, typically 21 years or above', 'Regular source of income (salaried or self-employed)', 'Valid KYC documents', 'Acceptable credit history as per the lender'],
        'documents'   => ['Identity proof: PAN, Aadhaar or equivalent', 'Address proof', 'Income proof or recent bank statements', 'Passport-size photographs', 'Vehicle quotation from the dealer'],
        'faqs' => [
            ['What is a bike loan?', 'A bike loan is financing that lets you buy a two-wheeler and repay the cost in monthly instalments over an agreed tenure.'],
            ['Who decides the interest rate and tenure?', 'The lending institution sets the interest rate, tenure and charges based on its own policies and your profile.'],
            ['What documents will I need?', 'Lenders commonly ask for identity, address and income proof along with a dealer quotation. The exact list depends on the lender.'],
            ['How do I get started?', 'Use the enquiry form on this page, share your requirement and our team will guide you through the next steps.'],
        ],
        'calc' => ['amount' => 100000, 'min' => 20000, 'max' => 500000, 'step' => 5000, 'years' => 3, 'maxYears' => 7],
    ],

    'gold-loan' => [
        'h1'    => 'Make your gold <em>work for you.</em>',
        'lead'  => 'Quick financial support against your gold. Share your requirement and explore suitable options.',
        'img'   => 'gold.jpg',
        'intro' => [
            'A gold loan lets you borrow against gold ornaments you already own, without selling them.',
            'Fincrest 360 helps you understand your options and connect with the relevant lending institution.',
        ],
        'highlights' => ['Borrow against gold', 'Guided enquiry', 'Repayment planning', 'Clear communication'],
        'features' => [
            ['Borrow against gold', 'Use your gold ornaments as security for a loan.'],
            ['Guided enquiry', 'Clear support from your first enquiry to the next step.'],
            ['Transparent communication', 'Straightforward information about the process.'],
            ['Repayment planning', 'Estimate your repayments before you proceed.'],
        ],
        'eligibility' => ['Indian resident, typically 18 years or above', 'Gold ornaments that meet the lender\'s purity norms', 'Valid KYC documents', 'Terms as per the lending institution'],
        'documents'   => ['Identity proof: PAN, Aadhaar or equivalent', 'Address proof', 'Passport-size photographs', 'Gold ornaments for valuation by the lender'],
        'faqs' => [
            ['What is a gold loan?', 'A gold loan is a secured loan where you pledge gold ornaments with a lender and receive funds against their assessed value.'],
            ['Who values my gold?', 'The lending institution assesses the purity and weight of your gold according to its own process.'],
            ['Who decides the interest rate and tenure?', 'The lending institution sets the interest rate, tenure and charges based on its own policies.'],
            ['How do I get started?', 'Use the enquiry form on this page, share your requirement and our team will guide you through the next steps.'],
        ],
        'calc' => ['amount' => 100000, 'min' => 20000, 'max' => 2000000, 'step' => 10000, 'years' => 1, 'maxYears' => 5],
    ],

    'personal-loan' => [
        'h1'    => 'Fund life\'s important moments <em>with confidence.</em>',
        'lead'  => 'Flexible unsecured financing for planned costs, milestones and unexpected needs.',
        'img'   => 'personal.jpg',
        'intro' => [
            'A personal loan is an unsecured loan, which means you do not need to pledge an asset as security. It can be used for many personal needs.',
            'Fincrest 360 helps you explore options and understand your repayments before you proceed.',
        ],
        'highlights' => ['Unsecured', 'Predictable payments', 'Simple enquiry', 'Flexible use'],
        'features' => [
            ['No collateral', 'Explore financing without pledging an asset.'],
            ['Predictable payments', 'Plan fixed monthly instalments with the EMI calculator.'],
            ['Simple enquiry', 'Share your requirement in a few easy steps.'],
            ['Clear information', 'Understand your repayments before you proceed.'],
        ],
        'eligibility' => ['Minimum age as per the lender', 'Regular source of income (salaried or self-employed)', 'Acceptable credit history as per the lender', 'Active bank account in your name'],
        'documents'   => ['Identity proof: PAN, Aadhaar or equivalent', 'Address proof: utility bill or other accepted record', 'Income proof: salary slips or bank statements', 'Passport-size photographs'],
        'faqs' => [
            ['What is a personal loan?', 'A personal loan is an unsecured loan repaid in monthly instalments. The lender decides the amount, rate and tenure.'],
            ['Do I need to give security?', 'Personal loans are generally unsecured, so no asset needs to be pledged. Conditions are set by the lender.'],
            ['Who decides the interest rate and tenure?', 'The lending institution decides these based on its own policies and your profile.'],
            ['How do I get started?', 'Use the enquiry form on this page. Our team will get in touch with the next steps.'],
        ],
        'calc' => ['amount' => 300000, 'min' => 50000, 'max' => 3000000, 'step' => 10000, 'years' => 3, 'maxYears' => 7],
    ],

    'business-loan' => [
        'h1'    => 'Fuel your business today for <em>a bigger tomorrow.</em>',
        'lead'  => 'Explore business loan options designed around how your business operates and grows.',
        'img'   => 'business.jpg',
        'intro' => [
            'A business loan provides funds for working capital, expansion, equipment or other business needs, repaid in instalments over a chosen tenure.',
            'Fincrest 360 helps you explore options and continue with the relevant lending institution.',
        ],
        'highlights' => ['Working capital', 'Business expansion', 'Equipment needs', 'Guided process'],
        'features' => [
            ['Flexible structures', 'Explore options across lending institutions.'],
            ['Growth-focused', 'Financing for expansion, inventory, equipment and more.'],
            ['Guided support', 'Clear next steps from enquiry to lender.'],
            ['EMI planning', 'Estimate your repayments before you proceed.'],
        ],
        'eligibility' => ['Business vintage and turnover as per the lender', 'Stable cash flow and repayment capacity', 'Acceptable credit history of business and owners', 'Valid KYC and business registration documents'],
        'documents'   => ['Identity and address proof of owners', 'Business registration documents', 'Bank statements', 'Income tax returns and financial statements', 'Any other documents requested by the lender'],
        'faqs' => [
            ['What can a business loan be used for?', 'Business loans are commonly used for working capital, expansion, equipment and other business needs. Permitted use is set by the lender.'],
            ['Is collateral required?', 'This depends on the loan type and the lending institution. Some business loans are unsecured; others may need security.'],
            ['Who decides the interest rate and tenure?', 'The lending institution decides these based on its own policies and your business profile.'],
            ['How do I get started?', 'Use the enquiry form on this page. Our team will get in touch with the next steps.'],
        ],
        'calc' => ['amount' => 1000000, 'min' => 100000, 'max' => 20000000, 'step' => 50000, 'years' => 5, 'maxYears' => 15],
    ],
];
