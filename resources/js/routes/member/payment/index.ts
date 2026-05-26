import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\PaymentController::initiate
 * @see app/Http/Controllers/PaymentController.php:40
 * @route '/membre/paiement'
 */
export const initiate = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: initiate.url(options),
    method: 'post',
})

initiate.definition = {
    methods: ["post"],
    url: '/membre/paiement',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PaymentController::initiate
 * @see app/Http/Controllers/PaymentController.php:40
 * @route '/membre/paiement'
 */
initiate.url = (options?: RouteQueryOptions) => {
    return initiate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PaymentController::initiate
 * @see app/Http/Controllers/PaymentController.php:40
 * @route '/membre/paiement'
 */
initiate.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: initiate.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\PaymentController::initiate
 * @see app/Http/Controllers/PaymentController.php:40
 * @route '/membre/paiement'
 */
    const initiateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: initiate.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PaymentController::initiate
 * @see app/Http/Controllers/PaymentController.php:40
 * @route '/membre/paiement'
 */
        initiateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: initiate.url(options),
            method: 'post',
        })
    
    initiate.form = initiateForm
const payment = {
    initiate: Object.assign(initiate, initiate),
}

export default payment