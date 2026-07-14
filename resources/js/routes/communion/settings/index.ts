import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\CommunionController::update
 * @see app/Http/Controllers/CommunionController.php:30
 * @route '/sainte-cene/configurer'
 */
export const update = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '/sainte-cene/configurer',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\CommunionController::update
 * @see app/Http/Controllers/CommunionController.php:30
 * @route '/sainte-cene/configurer'
 */
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CommunionController::update
 * @see app/Http/Controllers/CommunionController.php:30
 * @route '/sainte-cene/configurer'
 */
update.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\CommunionController::update
 * @see app/Http/Controllers/CommunionController.php:30
 * @route '/sainte-cene/configurer'
 */
    const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\CommunionController::update
 * @see app/Http/Controllers/CommunionController.php:30
 * @route '/sainte-cene/configurer'
 */
        updateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(options),
            method: 'post',
        })
    
    update.form = updateForm
const settings = {
    update: Object.assign(update, update),
}

export default settings