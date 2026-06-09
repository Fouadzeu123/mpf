import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\PaymentController::contribute
 * @see app/Http/Controllers/PaymentController.php:100
 * @route '/membre/evenements/{event}/contribuer'
 */
export const contribute = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: contribute.url(args, options),
    method: 'post',
})

contribute.definition = {
    methods: ["post"],
    url: '/membre/evenements/{event}/contribuer',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PaymentController::contribute
 * @see app/Http/Controllers/PaymentController.php:100
 * @route '/membre/evenements/{event}/contribuer'
 */
contribute.url = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { event: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { event: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    event: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        event: typeof args.event === 'object'
                ? args.event.id
                : args.event,
                }

    return contribute.definition.url
            .replace('{event}', parsedArgs.event.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PaymentController::contribute
 * @see app/Http/Controllers/PaymentController.php:100
 * @route '/membre/evenements/{event}/contribuer'
 */
contribute.post = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: contribute.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\PaymentController::contribute
 * @see app/Http/Controllers/PaymentController.php:100
 * @route '/membre/evenements/{event}/contribuer'
 */
    const contributeForm = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: contribute.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PaymentController::contribute
 * @see app/Http/Controllers/PaymentController.php:100
 * @route '/membre/evenements/{event}/contribuer'
 */
        contributeForm.post = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: contribute.url(args, options),
            method: 'post',
        })
    
    contribute.form = contributeForm
const events = {
    contribute: Object.assign(contribute, contribute),
}

export default events