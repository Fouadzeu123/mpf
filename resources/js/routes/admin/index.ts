import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\FeedController::feed
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/flux'
 */
export const feed = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: feed.url(options),
    method: 'get',
})

feed.definition = {
    methods: ["get","head"],
    url: '/flux',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\FeedController::feed
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/flux'
 */
feed.url = (options?: RouteQueryOptions) => {
    return feed.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\FeedController::feed
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/flux'
 */
feed.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: feed.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\FeedController::feed
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/flux'
 */
feed.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: feed.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\FeedController::feed
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/flux'
 */
    const feedForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: feed.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\FeedController::feed
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/flux'
 */
        feedForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: feed.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\FeedController::feed
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/flux'
 */
        feedForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: feed.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    feed.form = feedForm
const admin = {
    feed: Object.assign(feed, feed),
}

export default admin