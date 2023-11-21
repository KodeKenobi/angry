const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor;
const { TextControl } = wp.components;

registerBlockType('custom-product-block/block', {
    title: 'Custom Product Block',
    icon: 'cart',
    category: 'common',
    attributes: {
        heading: {
            type: 'string',
            default: 'Featured Products',
        },
    },
    edit: function (props) {
        const { attributes, setAttributes } = props;

        return (
            <div>
                <InspectorControls>
                    <TextControl
                        label="Block Heading"
                        value={attributes.heading}
                        onChange={(newHeading) => setAttributes({ heading: newHeading })}
                    />
                </InspectorControls>
                <p>{attributes.heading}</p>
            </div>
        );
    },
    save: function () {
        return null;
    },
    example: () => {
        return <div className="custom-product-block-preview">Example Preview</div>;
    },
    supports: {
        example: true,
    },
});

