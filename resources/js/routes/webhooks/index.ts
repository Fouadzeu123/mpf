import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\WebhookController::notchpay
 * @see app/Http/Controllers/WebhookController.php:21
 * @route '/webhooks/notchpay'
 */
export const notchpay = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: notchpay.url(options),
    method: 'post',
})

notchpay.definition = {
    methods: ["post"],
    url: '/webhooks/notchpay',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\WebhookController::notchpay
 * @see app/Http/Controllers/WebhookController.php:21
 * @route '/webhooks/notchpay'
 */
notchpay.url = (options?: RouteQueryOptions) => {
    return notchpay.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\WebhookController::notchpay
 * @see app/Http/Controllers/WebhookController.php:21
 * @route '/webhooks/notchpay'
 */
notchpay.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: notchpay.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\WebhookController::notchpay
 * @see app/Http/Controllers/WebhookController.php:21
 * @route '/webhooks/notchpay'
 */
    const notchpayForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: notchpay.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\WebhookController::notchpay
 * @see app/Http/Controllers/WebhookController.php:21
 * @route '/webhooks/notchpay'
 */
        notchpayForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: notchpay.url(options),
            method: 'post',
        })
    
    notchpay.form = notchpayForm
const webhooks = {
    notchpay: Object.assign(notchpay, notchpay),
}

export default webhooks